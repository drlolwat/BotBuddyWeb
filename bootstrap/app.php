<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(
            remove: [\Illuminate\Routing\Middleware\ThrottleRequests::class.':api']
        );

        //$middleware->web(
        //    append: [HandleInertiaRequests::class]
        //);

        $middleware->validateCsrfTokens(except: [
            'store/webhook',
        ]);

        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'has.never.subscribed' => \App\Http\Middleware\HasNeverSubscribed::class,
            'subscription.expire.warning' => \App\Http\Middleware\SubscriptionExpireWarning::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        Integration::handles($exceptions);
    })->create();
