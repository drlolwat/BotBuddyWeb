<?php

namespace App\Http\Controllers;

use App\BotBuddy\Sellix\SellixService;
use function Sentry\captureException;
use Throwable;

class StoreController extends Controller
{
    public function index()
    {
        return view('v1.store');
    }

    public function checkout($product, SellixService $sellix)
    {
        // todo: replace with configurable values
        $products = ['tier1' => '64658dbf9b949'];

        if (!in_array($product, array_keys($products))) {
            return back()->withErrors('This product does not exist.');
        }

        if (!auth()->user()->sellix_customer_uniqid) {
            $customer_payload = [
                'email' => auth()->user()->email,
                'name' => 'BotBuddy',
                'surname' => 'User',
            ];

            try {
                $customer_id = $sellix->client->create_customer($customer_payload);
                auth()->user()->update(['sellix_customer_uniqid' => $customer_id]);
            } catch (Throwable $e) {
                captureException($e);
                return back()->withErrors('Something went wrong. Please try again later.');
            }
        }

        $subscription_payload = [
            'product_id' => $products[$product],
            'coupon_code' => null,
            'custom_fields' => [
                'user_id' => auth()->user()->id,
            ],
            'customer_id' => auth()->user()->sellix_customer_uniqid,
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
}
