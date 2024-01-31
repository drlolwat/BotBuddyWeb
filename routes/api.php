<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('updateBot', [\App\Http\Controllers\Api\AccountController::class, 'updateBot']);
Route::post('wrapper', [\App\Http\Controllers\Api\AccountController::class, 'wrapper']);
Route::post('agentKey', [\App\Http\Controllers\Api\AgentController::class, 'agentKey']);
Route::post('agentData', [\App\Http\Controllers\Api\AgentController::class, 'agentData']);
Route::post('customerId', [\App\Http\Controllers\Api\AgentController::class, 'customerId']);
