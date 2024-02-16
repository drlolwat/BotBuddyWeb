<?php

namespace App\Http\Controllers;

use App\BotBuddy\Sellix\SellixService;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use function Sentry\captureException;
use Throwable;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
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
        captureException(new \Exception(json_encode($request->toArray())));
    }
}
