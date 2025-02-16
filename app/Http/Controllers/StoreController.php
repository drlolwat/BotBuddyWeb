<?php

namespace App\Http\Controllers;

use App\BotBuddy\Sellix\SellixService;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\Webhook;
use function Sentry\captureException;
use Throwable;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth'])->except('webhook');
        $this->middleware('subscription.expire.warning');
    }

    public function index(): View
    {
        $subscriptions = Subscription::query()
            ->where('name', '!=', 'Founder')
            ->orderBy('id')
            ->get();

        // map $subscriptions by slug
        $subscriptions = $subscriptions->mapWithKeys(function ($subscription) {
            return [$subscription->slug => $subscription];
        });

        return view('v1.store', compact('subscriptions'));
    }

    public function checkout(string $product): RedirectResponse
    {
        if (config('stripe.secret') === null) {
            return back()->withErrors('The store will be available soon.');
        }

        $subscription = Subscription::query()
            ->where('slug', $product)
            ->where('name', '!=', 'Founder')
            ->first();

        if (!$subscription) {
            return back()->withErrors('This product does not exist.');
        }

        $interval = 'month';

        if (Str::endsWith('-annually', $subscription->slug)) {
            $interval = 'year';
        }

        $intervalFormatted = match ($interval) {
            'month' => 'Monthly',
            'year' => 'Yearly',
        };

        $price = match ($subscription->slug) {
            'basic-monthly' => 9_99,
            'essential-monthly' => 19_99,
            'farm-monthly' => 39_99,
            'basic-annually' => 95_88,
            'essential-annually' => 191_88,
            'farm-annually' => 383_88,
        };

        if ($price === 0) {
            return back()->withErrors('Something went wrong. Please try again later.');
        }

        /** @var User $user */
        $user = auth()->user();

        Stripe::setApiKey(config('stripe.secret'));

        try {
            if (!$user->stripe_customer_id) {
                $customer = Customer::create([
                    'email' => $user->email,
                    'name' => $user->name,
                ]);

                $user->stripe_customer_id = $customer->id;
                $user->save();
            }
        } catch (Throwable $e) {
            captureException($e);
            return back()->withErrors('Failed to create or retrieve customer.');
        }

        try {
            $session = Session::create([
                'customer' => $user->stripe_customer_id,
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'usd',
                        'unit_amount'  => $price,
                        'recurring'    => ['interval' => $interval],
                        'product_data' => [
                            'name' => "{$subscription->name} - {$intervalFormatted}",
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => route('checkout.success'),
                'cancel_url' => route('checkout.cancel'),
            ]);

            return redirect($session->url);
        } catch(Throwable $e) {
            captureException($e);
            return back()->withErrors('Failed to create checkout session.');
        }
    }

    public function webhook(Request $request)
    {
        $payload = @file_get_contents('php://input');
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch(\UnexpectedValueException $e) {
            captureException(new \Exception("failed to parse webhook: {$payload}"));
            return response()->json(['error' => 'Invalid request'], 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            try {
                $amount = $event->data->object->amount;
                $customer = $event->data->object->customer;

                if (!$amount || $amount === 0) {
                    captureException(new \Exception("Invalid amount"));
                    return response()->json(['error' => 'Invalid amount'], 401);
                }

                if (!$customer) {
                    captureException(new \Exception("No customer provided"));
                    return response()->json(['error' => 'Invalid customer'], 400);
                }

                $subscriptions = array_flip([
                    'basic-monthly' => 9_99,
                    'essential-monthly' => 19_99,
                    'farm-monthly' => 39_99,
                    'basic-annually' => 95_88,
                    'essential-annually' => 191_88,
                    'farm-annually' => 383_88,
                ]);

                $subscription_slug = $subscriptions[$amount];
                $subscription = Subscription::query()->where('slug', $subscription_slug)->first();

                $user = User::query()->where('stripe_customer_id', $customer)->first();
                if (!$user) {
                    captureException(new \Exception("User not found for customer: {$customer}"));
                    return response()->json(['error' => 'Invalid user'], 400);
                }

                $expired = $user->subscription_expires_at->isPast();

                if (!$user->subscription_expires_at || $expired) {
                    $user->subscription_expires_at = now();
                }

                if ($user->subscription_id != $subscription->id) {
                    captureException(new \Exception("user $user->id has diff existing sub $user->subscription_id, expiry $user->subscription_expires_at"));
                    $user->subscription_expires_at = now();
                }

                if (Str::endsWith($subscription_slug, '-annually')) {
                    $user->subscription_expires_at = $user->subscription_expires_at->addYear();
                } else {
                    $user->subscription_expires_at = $user->subscription_expires_at->addMonth();
                }

                $user->subscription_id = $subscription->id;
                $user->save();
            } catch (Throwable $e) {
                captureException($e);
                return response()->json(['error' => 'Something went wrong'], 406);
            }
        }

        return response()->json(['status' => 'success'], 200);
    }

    public function checkout_sellix(string $product, SellixService $sellix): RedirectResponse
    {
        $subscriptions = Subscription::query()
            ->where('name', '!=', 'Founder')
            ->orderBy('id')
            ->get();

        // map $subscriptions by slug
        $products = $subscriptions->mapWithKeys(function ($subscription) {
            return [$subscription->slug => $subscription->product_id];
        });

        if (!in_array($product, array_keys($products->toArray()))) {
            return back()->withErrors('This product does not exist.');
        }

        /** @var User $user */
        $user = auth()->user();

        if (!$user->sellix_customer_uniqid) {
            $customer_payload = [
                'email' => $user->email,
                'name' => 'BotBuddy',
                'surname' => 'User',
            ];

            try {
                try {
                    /** @phpstan-ignore-next-line */
                    $user->sellix_customer_uniqid = $sellix->client->create_customer($customer_payload);
                    $user->save();
                } catch (Throwable $e) {
                    /** @phpstan-ignore-next-line */
                    $customer = $sellix->client->get_customer($user->email);
                    if ($customer) {
                        $user->sellix_customer_uniqid = $customer->id;
                        $user->save();
                    } else {
                        captureException(new \Exception("Failed to create customer or get existing customer: {$user->email}"));
                        return back()->withErrors('Something went wrong. Please try again later.');
                    }
                }
            } catch (Throwable $e) {
                captureException($e);
                return back()->withErrors('Something went wrong. Please try again later.');
            }
        }

        $subscription_payload = [
            'product_id' => $products[$product],
            'coupon_code' => null,
            'custom_fields' => [
                'user_id' => $user->id,
            ],
            'customer_id' => $user->sellix_customer_uniqid,
            'gateway' => null,
        ];

        try {
            /** @phpstan-ignore-next-line */
            $subscription = $sellix->client->create_subscription($subscription_payload);
            // $subscription->uniqid for the subscription id
            if (!$subscription) {
                captureException(new \Exception("Subscription variable is null?"));
                return back()->withErrors('Something went wrong. Please try again later.');
            }
            if (!$subscription->url) {
                captureException(new \Exception("Subscription url not available: " . json_encode($subscription)));
                return back()->withErrors('Something went wrong. Please try again later.');
            }
            return redirect($subscription->url);
        } catch (\Exception $e) {
            captureException($e);
            return back()->withErrors('Something went wrong. Please try again later.');
        }
    }

    public function webhook_sellix(Request $request): void
    {
        $payload = $request->all();

        //example webhook: {"event":"subscription:created","data":{"id":"rec_e545cd-bac067b3f2-318ec4","shop_id":323239,"status":"ACTIVE","gateway":null,"custom_fields":{"user_id":1},"customer_id":"cst_4478ea002453d1efad2eb1","stripe_customer_id":null,"stripe_subscription_id":null,"stripe_account":"acct_1M6PrBC46qr3SE33","paypal_subscription_id":null,"paypal_account":"YF7FY3AXM3PYS","product_id":"64658dbf9b949","coupon_id":null,"current_period_end":1710595973,"upcoming_email_1_week_sent":0,"trial_period_ending_email_sent":0,"renewal_invoice_created":0,"status_details":null,"created_at":1708091455,"updated_at":null,"canceled_at":null,"shop_name":"BotBuddy","product_title":"BotBuddy Basic","cloudflare_image_id":null,"customer_name":"BotBuddy","customer_surname":"User","customer_phone":null,"customer_phone_country_code":null,"customer_country_code":null,"customer_street_address":null,"customer_additional_address_info":null,"customer_city":null,"customer_postal_code":null,"customer_state":null,"customer_email":"demo@botbuddy.net","invoices":[{"id":"10143718","uniqid":"521792-ea07982b56-0d23bd","recurring_billing_id":"rec_e545cd-bac067b3f2-318ec4","total":"10.00","total_display":"10.00","exchange_rate":"1.00000000","crypto_exchange_rate":"0.00000000","currency":"USD","shop_id":"323239","product_id":"64658dbf9b949","gateway":"null","paypal_apm":"null","stripe_apm":"null","quantity":"1","coupon_id":"null","status":"COMPLETED","status_details":"COMPLETED_NO_PAYOUT","void_details":"null","discount":"0.00","created_at":"1708090373","updated_at":"1708091457"}],"approved_address":null,"gateways_available":[]}}
        //captureException(new \Exception(json_encode($request->toArray())));

        switch ($payload['event']) {
            case 'subscription:created':
            case 'subscription:renewed':
            case 'subscription:updated':
                $subscription = Subscription::query()->where('product_id', $payload['data']['product_id'])->first();
                if (!$subscription) {
                    throw new \Exception("Subscription product_id not found: {$payload['data']['product_id']}");
                }
                $user = User::query()->where('id', $payload['data']['custom_fields']['user_id'])->first();
                if (!$user) {
                    throw new \Exception("User not found: {$payload['data']['custom_fields']['user_id']}");
                }

                $user->subscription_id = $subscription->id;
                $user->subscription_expires_at = Carbon::createFromTimestamp($payload['data']['current_period_end']);
                $user->save();
                break;
            case 'subscription:cancelled':
                // do nothing? they can have access until the end of the period
                break;
        }
    }
}
