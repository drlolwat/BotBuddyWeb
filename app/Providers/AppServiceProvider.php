<?php

namespace App\Providers;

use App\BotBuddy\Workflow\WorkflowService;
use App\BotBuddy\Socket\SocketService;
use App\Models\Account;
use App\Models\AccountGroup;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SocketService::class, function () {
            return new SocketService();
        });

        $this->app->singleton(WorkflowService::class, function () {
            return new WorkflowService();
        });

        Relation::morphMap([
            'account' => Account::class,
            'account_group' => AccountGroup::class,
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
