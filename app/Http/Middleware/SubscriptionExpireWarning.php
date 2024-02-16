<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionExpireWarning
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            auth()->check() && auth()->user()->subscription_expires_at &&
            auth()->user()->subscription_expires_at->isFuture() &&
            auth()->user()->subscription_expires_at->diffInDays(now()) <= 7) {

            $diffForHumans = now()->diffForHumans(auth()->user()->subscription_expires_at, true, false, 1);

            session()->flash('warning', 'Your subscription is expiring in ' . $diffForHumans . '. Please renew your subscription.');
        }

        return $next($request);
    }
}
