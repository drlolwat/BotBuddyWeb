<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\WorkflowController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('index');

Route::group(['middleware' => ['verified', 'has.never.subscribed']], function() {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/dark_mode', [SettingsController::class, 'dark_mode'])->name('settings.dark_mode');
    Route::post('/settings/email', [SettingsController::class, 'email'])->name('settings.email');
    Route::post('/settings/password', [SettingsController::class, 'password'])->name('settings.password');

    Route::group(['prefix' => 'account/group'], function () {
        Route::get('/', [App\Http\Controllers\AccountGroupController::class, 'index'])->name('account.group');
        Route::post('/', [App\Http\Controllers\AccountGroupController::class, 'store'])->name('account.group.store');
        Route::get('/create', [App\Http\Controllers\AccountGroupController::class, 'create'])->name('account.group.create');
        Route::get('/{group}', [App\Http\Controllers\AccountGroupController::class, 'show'])->name('account.group.show');
        Route::put('/{group}', [App\Http\Controllers\AccountGroupController::class, 'update'])->name('account.group.update');
        Route::delete('/{group}', [App\Http\Controllers\AccountGroupController::class, 'destroy'])->name('account.group.destroy');
        Route::get('/{group}/delete_confirm', [App\Http\Controllers\AccountGroupController::class, 'delete_confirm'])->name('account.group.delete_confirm');
        Route::post('/start/{group}', [App\Http\Controllers\AccountGroupController::class, 'start'])->name('account.group.start');
        Route::post('/stop/{group}', [App\Http\Controllers\AccountGroupController::class, 'stop'])->name('account.group.stop');
        Route::post('/queue/{group}', [App\Http\Controllers\AccountGroupController::class, 'queue'])->name('account.group.queue');
        Route::post('/dequeue/{group}', [App\Http\Controllers\AccountGroupController::class, 'dequeue'])->name('account.group.dequeue');
        Route::post('/change_proxy/{group}', [App\Http\Controllers\AccountGroupController::class, 'change_proxy'])->name('account.group.change_proxy');
        Route::post('/remove_proxy/{group}', [App\Http\Controllers\AccountGroupController::class, 'remove_proxy'])->name('account.group.remove_proxy');
        Route::post('/export/{group}', [App\Http\Controllers\AccountGroupController::class, 'export'])->name('account.group.export');
        Route::get('/{group}/schedule/create', [App\Http\Controllers\AccountGroupController::class, 'schedule_create_event'])->name('account.group.schedule.create');
        Route::post('/{group}/schedule/create', [App\Http\Controllers\AccountGroupController::class, 'schedule_create_event_submit']);
        Route::get('/{group}/schedule/{event}', [App\Http\Controllers\AccountGroupController::class, 'schedule_event'])->name('account.group.schedule.event.show');
        Route::put('/{group}/schedule/{event}', [App\Http\Controllers\AccountGroupController::class, 'schedule_event_update'])->name('account.group.schedule.event.update');
        Route::delete('/{group}/schedule/{event}', [App\Http\Controllers\AccountGroupController::class, 'schedule_event_destroy'])->name('account.group.schedule.event.destroy');
    });

    Route::group(['prefix' => 'account'], function () {
        Route::get('/import', [App\Http\Controllers\AccountController::class, 'import'])->name('account.import');
        Route::post('/import', [App\Http\Controllers\AccountController::class, 'importStore'])->name('account.import.store');
        Route::get('/', [App\Http\Controllers\AccountController::class, 'index'])->name('account');
        Route::post('/', [App\Http\Controllers\AccountController::class, 'store'])->name('account.store');
        Route::get('/create', [App\Http\Controllers\AccountController::class, 'create'])->name('account.create');
        Route::get('/{account}', [App\Http\Controllers\AccountController::class, 'show'])->name('account.show');
        Route::put('/{account}', [App\Http\Controllers\AccountController::class, 'update'])->name('account.update');
        Route::delete('/{account}', [App\Http\Controllers\AccountController::class, 'destroy'])->name('account.destroy');
        Route::post('/start/{account}', [App\Http\Controllers\AccountController::class, 'start'])->name('account.start');
        Route::post('/stop/{account}', [App\Http\Controllers\AccountController::class, 'stop'])->name('account.stop');
        Route::post('/bulkAction', [App\Http\Controllers\AccountController::class, 'bulkAction'])->name('account.bulkAction');
        Route::post('/dequeue/{account}', [App\Http\Controllers\AccountController::class, 'dequeue'])->name('account.dequeue');
    });

    Route::group(['prefix' => 'proxy/group'], function () {
        Route::get('/', [App\Http\Controllers\ProxyGroupController::class, 'index'])->name('proxy.group');
        Route::post('/', [App\Http\Controllers\ProxyGroupController::class, 'store'])->name('proxy.group.store');
        Route::get('/create', [App\Http\Controllers\ProxyGroupController::class, 'create'])->name('proxy.group.create');
        Route::get('/{group}', [App\Http\Controllers\ProxyGroupController::class, 'show'])->name('proxy.group.show');
        Route::put('/{group}', [App\Http\Controllers\ProxyGroupController::class, 'update'])->name('proxy.group.update');
        Route::delete('/{group}', [App\Http\Controllers\ProxyGroupController::class, 'destroy'])->name('proxy.group.destroy');
        Route::get('/{group}/delete_confirm', [App\Http\Controllers\ProxyGroupController::class, 'delete_confirm'])->name('proxy.group.delete_confirm');
    });

    Route::group(['prefix' => 'proxy'], function () {
        Route::get('/import', [App\Http\Controllers\ProxyController::class, 'import'])->name('proxy.import');
        Route::post('/import', [App\Http\Controllers\ProxyController::class, 'importStore'])->name('proxy.import.store');
        Route::get('/', [App\Http\Controllers\ProxyController::class, 'index'])->name('proxy');
        Route::post('/', [App\Http\Controllers\ProxyController::class, 'store'])->name('proxy.store');
        Route::get('/create', [App\Http\Controllers\ProxyController::class, 'create'])->name('proxy.create');
        Route::get('/{proxy}', [App\Http\Controllers\ProxyController::class, 'show'])->name('proxy.show');
        Route::put('/{proxy}', [App\Http\Controllers\ProxyController::class, 'update'])->name('proxy.update');
        Route::delete('/{proxy}', [App\Http\Controllers\ProxyController::class, 'destroy'])->name('proxy.destroy');
    });

    Route::group(['prefix' => 'agent'], function () {
        Route::get('/', [App\Http\Controllers\AgentController::class, 'index'])->name('agent');
        Route::post('/', [App\Http\Controllers\AgentController::class, 'store'])->name('agent.store');
        Route::get('/create', [App\Http\Controllers\AgentController::class, 'create'])->name('agent.create');
        Route::get('/{agent}', [App\Http\Controllers\AgentController::class, 'show'])->name('agent.show');
        Route::put('/{agent}', [App\Http\Controllers\AgentController::class, 'update'])->name('agent.update');
        Route::delete('/{agent}', [App\Http\Controllers\AgentController::class, 'destroy'])->name('agent.destroy');
        Route::get('/{agent}/download', [App\Http\Controllers\AgentController::class, 'download'])->name('agent.download');
    });

    Route::group(['prefix' => 'script'], function () {
        Route::get('/', [App\Http\Controllers\ScriptController::class, 'index'])->name('script');
        Route::post('/', [App\Http\Controllers\ScriptController::class, 'store'])->name('script.store');
        Route::get('/create', [App\Http\Controllers\ScriptController::class, 'create'])->name('script.create');
        Route::get('/{script}', [App\Http\Controllers\ScriptController::class, 'show'])->name('script.show');
        Route::put('/{script}', [App\Http\Controllers\ScriptController::class, 'update'])->name('script.update');
        Route::delete('/{script}', [App\Http\Controllers\ScriptController::class, 'destroy'])->name('script.destroy');

        Route::get('/{script}/create_trigger', [App\Http\Controllers\ScriptTriggerController::class, 'create'])->name('script.trigger.create');
        Route::post('/{script}/create_trigger', [App\Http\Controllers\ScriptTriggerController::class, 'store'])->name('script.trigger.store');
    });

    Route::group(['prefix' => 'script/trigger'], function () {
        Route::get('/{trigger}', [App\Http\Controllers\ScriptTriggerController::class, 'show'])->name('script.trigger.show');
        Route::put('/{trigger}', [App\Http\Controllers\ScriptTriggerController::class, 'update'])->name('script.trigger.update');
        Route::delete('/{trigger}', [App\Http\Controllers\ScriptTriggerController::class, 'destroy'])->name('script.trigger.destroy');
        Route::post('/bulkAction', [App\Http\Controllers\ScriptTriggerController::class, 'bulkAction'])->name('script.trigger.bulkAction');
    });

    Route::group(['prefix' => 'osiris', 'middleware' => 'auth'], function () {
        Route::view('dashboard', 'osiris.dashboard');
    });

    Route::group(['prefix' => 'workflow', 'middleware' => 'auth'], function () {
        Route::get('/', [WorkflowController::class, 'index'])->name('workflow');
        Route::post('/', [WorkflowController::class, 'store'])->name('workflow.store');
        Route::get('/create', [WorkflowController::class, 'create'])->name('workflow.create');
        //Route::get('/{workflow}', [WorkflowController::class, 'show'])->name('workflow.show');
        //Route::put('/{workflow}', [WorkflowController::class, 'update'])->name('workflow.update');
        Route::delete('/{workflow}', [WorkflowController::class, 'destroy'])->name('workflow.destroy');
    });

    Route::group(['prefix' => 'api/user', 'middleware' => 'auth'], function () {

        // currently used for workflows
        Route::get('account', fn () => auth()->user()->accounts()->select('id', 'email')->get());
        Route::get('account/group', fn () => auth()->user()->account_groups()->select('id', 'name')->get());
        //Route::get('proxy', fn () => auth()->user()->proxies);
        Route::get('proxy/group', fn () => auth()->user()->proxy_groups()->select('id', 'name')->get());
        //Route::get('agent', fn () => auth()->user()->agents);
        Route::get('script', fn () => auth()->user()->scripts()->select('id', 'name')->get());
        Route::get('workflow/event', [WorkflowController::class, 'events'])->name('workflow.events');

        Route::get('getRunningBotsByClient', function(\App\BotBuddy\Socket\SocketService $socket) {
            echo $socket->dispatch(new \App\BotBuddy\Socket\Commands\GetRunningBotsByClient(auth()->user()));
        });
    });

    Route::middleware([App\Http\Middleware\HandleInertiaRequests::class])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications');
        Route::get('notifications/clear', [App\Http\Controllers\NotificationController::class, 'clear'])->name('notifications.clear');
        Route::get('test', fn() => inertia('Test'));
    });
});

Route::get('store', [\App\Http\Controllers\StoreController::class, 'index'])->name('store');
Route::get('store/{product}', [\App\Http\Controllers\StoreController::class, 'checkout'])->name('store.checkout');
Route::post('store/webhook', [\App\Http\Controllers\StoreController::class, 'webhook'])->name('store.webhook');
