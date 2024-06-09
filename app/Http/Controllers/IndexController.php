<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index(): View|RedirectResponse
    {
        if (auth()->check()) {
            return redirect(route('dashboard'));
        }

        $subscriptions = Subscription::query()
            ->where('name', '!=', 'Founder')
            ->orderBy('id')
            ->get();

        // map $subscriptions by slug
        $subscriptions = $subscriptions->mapWithKeys(function ($subscription) {
            return [$subscription->slug => $subscription];
        });

        return view('v1.landing', ['subscriptions' => $subscriptions]);
    }
}
