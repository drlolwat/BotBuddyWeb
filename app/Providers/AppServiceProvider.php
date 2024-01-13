<?php

namespace App\Providers;

use App\BotBuddy\Socket\SocketService;
use App\Models\Account;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('socket', function () {
            return new SocketService();
        });

        Relation::morphMap([
            'account' => Account::class,
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
