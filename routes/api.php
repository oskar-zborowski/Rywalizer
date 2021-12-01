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
    | Enpointy dostępne wyłącznie bez autoryzacji - w przypadku nowych pozycji należy uzupełnić middleware'y
    |-------------------------------------------------------------------------------------------------------
    */

    Route::middleware('before.auth')->group(function () {
        
        Route::post('/login', [AuthController::class, 'login'])->middleware(['throttle:loginLimit']);
        Route::post('/register', [AuthController::class, 'register'])->middleware(['throttle:registerLimit']);

        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::patch('/reset-password', [AuthController::class, 'resetPassword']);

        Route::patch('/email/verify', [AuthController::class, 'verifyEmail']);

        Route::post('/user', [AuthController::class, 'updateUser']);
    });

    /*
    |-------------------------------------------------------------------------------------------------------
    | Enpointy do zewnętrznego uwierzytelnienia, dostępne wyłącznie bez autoryzacji - jw.
    |-------------------------------------------------------------------------------------------------------
    */

    Route::get('auth/{provider}/redirect', [AuthController::class, 'redirectToProvider'])->middleware(['throttle:loginLimit']);
    Route::get('auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->withoutMiddleware('throttle:defaultAuthLimit');

    /*
    |-------------------------------------------------------------------------------------------------------
    | Enpointy dostępne po autoryzacji
    |-------------------------------------------------------------------------------------------------------
    */

    Route::post('/email/verification-notification', [AuthController::class, 'sendVerificationEmail']);

    Route::delete('/logout', [AuthController::class, 'logout'])->withoutMiddleware('throttle:defaultAuthLimit');
    Route::delete('/logout-other-devices', [AuthController::class, 'logoutOtherDevices'])->middleware(['throttle:logoutOtherDevicesLimit']);

    Route::get('/user', [AuthController::class, 'getUser']);

    /*
    |-------------------------------------------------------------------------------------------------------
    | Enpointy dostępne po autoryzacji oraz ze zweryfikowanym mailem
    |-------------------------------------------------------------------------------------------------------
    */

    Route::middleware('verified')->group(function () {
        // TODO
    });
});
