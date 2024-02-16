<?php

namespace App\Http\Controllers;

use App\BotBuddy\Sellix\SellixService;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function Sentry\captureException;
use Throwable;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except('webhook');
    }

    public function index()
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

    public function checkout($product, SellixService $sellix)
    {
        if (auth()->user()->id != 1) {
            return back()->withErrors('This store is currently disabled.');
        }

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

            if (!$user->sellix_customer_uniqid) {
                try {
                    $user->sellix_customer_uniqid = $sellix->client->create_customer($customer_payload);
                    $user->save();
                } catch (Throwable $e) {
                    captureException($e);
                    return back()->withErrors('Something went wrong. Please try again later.');
                }
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
            $subscription = $sellix->client->create_subscription($subscription_payload);
            // $subscription->uniqid for the subscription id
            return redirect($subscription->url);
        } catch (\Exception $e) {
            captureException($e);
            return back()->withErrors('Something went wrong. Please try again later.');
        }
    }

    public function webhook(Request $request)
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
