<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

/*
|----------------------------------------------------------------------------------------------------------
| API Routes
|----------------------------------------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['throttle:defaultAuthLimit', 'auth:sanctum'])->group(function () {

    /*
    |-------------------------------------------------------------------------------------------------------
    | Enpointy dostępne wyłącznie bez autoryzacji - w przypadku nowych pozycji należy uzupełnić before.auth
    |-------------------------------------------------------------------------------------------------------
    */

    Route::middleware('before.auth')->group(function () {

        Route::post('/login', [AuthController::class, 'login'])->middleware(['throttle:loginLimit']);
        Route::post('/register', [AuthController::class, 'register'])->middleware(['throttle:registerLimit']);

        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware(['throttle:forgotPasswordLimit']);
        Route::put('/reset-password', [AuthController::class, 'resetPassword'])->middleware(['throttle:resetPasswordLimit']);
    });

    /*
    |-------------------------------------------------------------------------------------------------------
    | Enpointy do zewnętrznego uwierzytelnienia, dostępne wyłącznie bez autoryzacji - jw.
    |-------------------------------------------------------------------------------------------------------
    */

    Route::get('auth/{provider}/redirect', [AuthController::class, 'redirectToProvider'])->middleware(['throttle:providerRedirectLimit']);
    Route::get('auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->withoutMiddleware('throttle:defaultAuthLimit');

    /*
    |-------------------------------------------------------------------------------------------------------
    | Enpointy dostępne po autoryzacji
    |-------------------------------------------------------------------------------------------------------
    */

    Route::get('/email/verification-notification', [AuthController::class, 'sendVerificationEmail']);
    Route::put('/verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');

    Route::delete('/logout', [AuthController::class, 'logout'])->withoutMiddleware('throttle:defaultAuthLimit');
    Route::delete('/logout-other-devices', [AuthController::class, 'logoutOtherDevices'])->middleware(['throttle:logoutOtherDevicesLimit']);

    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/fill-missing-user-info', [AuthController::class, 'fillMissingUserInfo']);
});

/*
|-----------------------------------------------------------------------------------------------------------
| Enpointy dostępne po autoryzacji oraz ze zweryfikowanym mailem
|-----------------------------------------------------------------------------------------------------------
*/

Route::middleware(['throttle:defaultAuthLimit', 'auth:sanctum', 'verified'])->group(function () {
    // TODO
});
