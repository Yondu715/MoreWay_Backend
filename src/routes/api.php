<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
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

Route::prefix('auth')
    ->group(function () {
        Route::middleware('auth:api')
            ->group(function () {
                Route::post('logout', [AuthController::class, 'logout']);
                Route::get('me', [AuthController::class, 'me']);
            });
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
    });

Route::prefix('users')
    ->middleware('auth:api', 'role:user')
    ->group(function () {
        Route::get('/', [UserController::class, 'getUsers']);
        Route::get('/{userId}', [UserController::class, 'getUser']);
        Route::patch('/{userId}', [UserController::class, 'changeData']);
        Route::delete('/{userId}', [UserController::class, 'delete']);
        Route::patch('/{userId}/avatar', [UserController::class, 'changeAvatar']);
        Route::post('/{userId}/password', [UserController::class, 'changePassword']);
    });
