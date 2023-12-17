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

Route::get('/account', [App\Http\Controllers\AccountController::class, 'index'])->name('account');
Route::get('/proxy', [App\Http\Controllers\ProxyController::class, 'index'])->name('proxy');
Route::get('/script', [App\Http\Controllers\ScriptController::class, 'index'])->name('script');



Route::get('account/create', [App\Http\Controllers\AccountController::class, 'create'])->name('account.create');
Route::get('/proxy/create', [App\Http\Controllers\ProxyController::class, 'create'])->name('proxy.create');
Route::get('/script/create', [App\Http\Controllers\ScriptController::class, 'create'])->name('script.create');
Route::get('/account/group/create', [App\Http\Controllers\AccountGroupController::class, 'create'])->name('account.group.create');
Route::get('/proxy/group/create', [App\Http\Controllers\ProxyGroupController::class, 'create'])->name('proxy.group.create');

Route::get('/account/{account}', [App\Http\Controllers\AccountController::class, 'show'])->name('account.show');
Route::get('/proxy/{proxy}', [App\Http\Controllers\ProxyController::class, 'show'])->name('proxy.show');
Route::get('/script/{script}', [App\Http\Controllers\ScriptController::class, 'show'])->name('script.show');
Route::get('/account/group/{group}', [App\Http\Controllers\AccountGroupController::class, 'show'])->name('account.group.show');
Route::get('/proxy/group/{group}', [App\Http\Controllers\ProxyGroupController::class, 'show'])->name('proxy.group.show');

Route::post('/account', [App\Http\Controllers\AccountController::class, 'store'])->name('account.store');
Route::post('/proxy', [App\Http\Controllers\ProxyController::class, 'store'])->name('proxy.store');
Route::post('/script', [App\Http\Controllers\ScriptController::class, 'store'])->name('script.store');
Route::post('/account/group', [App\Http\Controllers\AccountGroupController::class, 'store'])->name('account.group.store');
Route::post('/proxy/group', [App\Http\Controllers\ProxyGroupController::class, 'store'])->name('proxy.group.store');

Route::put('/account/{account}', [App\Http\Controllers\AccountController::class, 'update'])->name('account.update');
Route::put('/proxy/{proxy}', [App\Http\Controllers\ProxyController::class, 'update'])->name('proxy.update');
Route::put('/script/{script}', [App\Http\Controllers\ScriptController::class, 'update'])->name('script.update');
Route::put('/account/group/{group}', [App\Http\Controllers\AccountGroupController::class, 'update'])->name('account.group.update');
Route::put('/proxy/group/{group}', [App\Http\Controllers\ProxyGroupController::class, 'update'])->name('proxy.group.update');

Route::delete('/account/{account}', [App\Http\Controllers\AccountController::class, 'destroy'])->name('account.destroy');
Route::delete('/proxy/{proxy}', [App\Http\Controllers\ProxyController::class, 'destroy'])->name('proxy.destroy');
Route::delete('/script/{script}', [App\Http\Controllers\ScriptController::class, 'destroy'])->name('script.destroy');
Route::delete('/account/group/{group}', [App\Http\Controllers\AccountGroupController::class, 'destroy'])->name('account.group.destroy');
Route::delete('/proxy/group/{group}', [App\Http\Controllers\ProxyGroupController::class, 'destroy'])->name('proxy.group.destroy');
