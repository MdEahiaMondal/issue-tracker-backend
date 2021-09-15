<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [\App\Http\Controllers\Api\UserController::class, 'me']);
    Route::post('/change-password', [\App\Http\Controllers\Api\UserController::class, 'password']);
    Route::post('/change-details', [\App\Http\Controllers\Api\UserController::class, 'changeDetails']);
});

Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/password/forgot', [\App\Http\Controllers\Api\ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [\App\Http\Controllers\Api\ResetPasswordController::class, 'reset']);
