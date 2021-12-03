<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GitHubController;
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

/*
|-------------------------------------------------------------------------------------------------------
| Endpointy do odbierania informacji z GitHuba
|-------------------------------------------------------------------------------------------------------
*/

Route::post('/github/pull', [GitHubController::class, 'pull'])->middleware(['throttle:githubPullLimit']);

Route::middleware(['throttle:defaultAuthLimit', 'auth:sanctum'])->group(function () {

    /*
    |-------------------------------------------------------------------------------------------------------
    | Endpointy dostępne wyłącznie bez autoryzacji - w przypadku nowych pozycji należy uzupełnić middleware'y
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
    | Endpointy do zewnętrznego uwierzytelnienia, dostępne wyłącznie bez autoryzacji - jw.
    |-------------------------------------------------------------------------------------------------------
    */

    Route::get('auth/{provider}/redirect', [AuthController::class, 'redirectToProvider'])->middleware(['throttle:loginLimit']);
    Route::get('auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->withoutMiddleware('throttle:defaultAuthLimit');

    /*
    |-------------------------------------------------------------------------------------------------------
    | Endpointy dostępne po autoryzacji
    |-------------------------------------------------------------------------------------------------------
    */

    Route::post('/email/verification-notification', [AuthController::class, 'sendVerificationEmail']);

    Route::delete('/logout', [AuthController::class, 'logout'])->withoutMiddleware('throttle:defaultAuthLimit');
    Route::delete('/logout-other-devices', [AuthController::class, 'logoutOtherDevices'])->middleware(['throttle:logoutOtherDevicesLimit']);

    Route::get('/user', [AuthController::class, 'getUser']);

    /*
    |-------------------------------------------------------------------------------------------------------
    | Endpointy dostępne po zweryfikowaniu roli użytkownika
    |-------------------------------------------------------------------------------------------------------
    */

    Route::middleware('user.roles')->group(function () {

        /*
        |-------------------------------------------------------------------------------------------------------
        | Endpointy dostępne dla użytkownika ze zweryfikowanym mailem
        |-------------------------------------------------------------------------------------------------------
        */

        Route::middleware('verified')->group(function () {
            // TODO
        });
    });
});
