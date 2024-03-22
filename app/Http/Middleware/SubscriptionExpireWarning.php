<?php

namespace App\Http\Middleware;

use App\Models\User;
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
        if (!auth()->check()) {
            return $next($request);
        }

        /** @var User $user */
        $user = auth()->user();

        if (
            $user->subscription_expires_at &&
            $user->subscription_expires_at->isFuture() &&
            abs($user->subscription_expires_at->diffInDays(now())) <= 7
        ) {

            $diffForHumans = now()->diffForHumans($user->subscription_expires_at, 1);

            session()->flash('warning', 'Your subscription is expiring in ' . $diffForHumans . '. Please renew your subscription.');
        }

        return $next($request);
    }
}
