<?php

use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

Route::group(['prefix' => 'account'], function () {
    Route::get('/', [App\Http\Controllers\AccountController::class, 'index'])->name('account');
    Route::post('/', [App\Http\Controllers\AccountController::class, 'store'])->name('account.store');
    Route::get('/create', [App\Http\Controllers\AccountController::class, 'create'])->name('account.create');
    Route::get('/{account}', [App\Http\Controllers\AccountController::class, 'show'])->name('account.show');
    Route::put('/{account}', [App\Http\Controllers\AccountController::class, 'update'])->name('account.update');
    Route::delete('/{account}', [App\Http\Controllers\AccountController::class, 'destroy'])->name('account.destroy');
    Route::post('/start/{account}', [App\Http\Controllers\AccountController::class, 'start'])->name('account.start');
    Route::post('/stop/{account}', [App\Http\Controllers\AccountController::class, 'stop'])->name('account.stop');
});

Route::group(['prefix' => 'proxy'], function () {
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
});

Route::group(['prefix' => 'proxy/group'], function () {
    Route::get('/', [App\Http\Controllers\ProxyGroupController::class, 'index'])->name('proxy.group');
    Route::post('/', [App\Http\Controllers\ProxyGroupController::class, 'store'])->name('proxy.group.store');
    Route::get('/create', [App\Http\Controllers\ProxyGroupController::class, 'create'])->name('proxy.group.create');
    Route::get('/{group}', [App\Http\Controllers\ProxyGroupController::class, 'show'])->name('proxy.group.show');
    Route::put('/{group}', [App\Http\Controllers\ProxyGroupController::class, 'update'])->name('proxy.group.update');
    Route::delete('/{group}', [App\Http\Controllers\ProxyGroupController::class, 'destroy'])->name('proxy.group.destroy');
});

Route::group(['prefix' => 'account/group'], function () {
    Route::get('/', [App\Http\Controllers\AccountGroupController::class, 'index'])->name('account.group');
    Route::post('/', [App\Http\Controllers\AccountGroupController::class, 'store'])->name('account.group.store');
    Route::get('/create', [App\Http\Controllers\AccountGroupController::class, 'create'])->name('account.group.create');
    Route::get('/{group}', [App\Http\Controllers\AccountGroupController::class, 'show'])->name('account.group.show');
    Route::put('/{group}', [App\Http\Controllers\AccountGroupController::class, 'update'])->name('account.group.update');
    Route::delete('/{group}', [App\Http\Controllers\AccountGroupController::class, 'destroy'])->name('account.group.destroy');
});

Route::group(['prefix' => 'script'], function () {
    Route::get('/', [App\Http\Controllers\ScriptController::class, 'index'])->name('script');
    Route::post('/', [App\Http\Controllers\ScriptController::class, 'store'])->name('script.store');
    Route::get('/create', [App\Http\Controllers\ScriptController::class, 'create'])->name('script.create');
    Route::get('/{script}', [App\Http\Controllers\ScriptController::class, 'show'])->name('script.show');
    Route::put('/{script}', [App\Http\Controllers\ScriptController::class, 'update'])->name('script.update');
    Route::delete('/{script}', [App\Http\Controllers\ScriptController::class, 'destroy'])->name('script.destroy');
});
