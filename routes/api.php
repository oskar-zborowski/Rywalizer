<?php

use App\Http\Controllers\Api\AuthController;
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

Route::middleware('auth:sanctum')->group(function () {

    /*
    |----------------------------------------------------------------------
    | Enpointy dostępne bez autoryzacji
    |----------------------------------------------------------------------
    */

    Route::middleware('before.auth')->group(function () {

        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);

        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::put('/reset-password', [AuthController::class, 'resetPassword']);

        Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
    });

    /*
    |----------------------------------------------------------------------
    | Enpointy dostępne po autoryzacji, ale bez zweryfikowanego maila
    |----------------------------------------------------------------------
    */

    Route::post('/email/verification-notification', [AuthController::class, 'sendVerificationEmail']);
    Route::put('/verify-email/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');

    Route::delete('/logout', [AuthController::class, 'logout']);
});

/*
|--------------------------------------------------------------------------
| Enpointy dostępne po autoryzacji oraz ze zweryfikowanym mailem
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum', 'verified')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
});
