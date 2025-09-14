<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('throttle:5,1')->group(function () { // 1 分鐘內最多 5 次
    Route::get('/test', function () {
        return 'OK';
    });
});

Route::apiResource('users', UserController::class);
Route::apiResource('orders', OrderController::class);
Route::get('redis/set', [UserController::class, 'setRedis']);
Route::get('redis/get', [UserController::class, 'getRedis']);


