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

Route::middleware(['throttle:defaultLimit', 'auth:sanctum'])->group(function () {

    /*
    |-------------------------------------------------------------------------------------------------------
    | Endpointy wpływające na encję użytkownika i wymagające dodatkowego sprawdzenia poprawności danych -
    | w przypadku nowych pozycji należy uzupełnić middleware'y Authenticate.php oraz BeforeUser.php
    |-------------------------------------------------------------------------------------------------------
    */

    Route::middleware('before.user')->group(function () {
        
        Route::post('/auth/login', [AuthController::class, 'login'])->name('auth-login')->middleware(['throttle:loginLimit']);
        Route::post('/auth/register', [AuthController::class, 'register'])->name('auth-register')->middleware(['throttle:registerLimit']);

        Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword'])->name('auth-forgotPassword');
        Route::patch('/auth/reset-password', [AuthController::class, 'resetPassword'])->name('auth-resetPassword');

        Route::patch('/email/verify', [AuthController::class, 'verifyEmail'])->name('auth-verifyEmail');

        Route::patch('/user', [AuthController::class, 'updateUser'])->name('auth-updateUser');
        Route::post('/user/avatar/upload', [AuthController::class, 'uploadAvatar'])->name('auth-uploadAvatar');
    });

    /*
    |-------------------------------------------------------------------------------------------------------
    | Endpointy do zewnętrznego uwierzytelnienia
    |-------------------------------------------------------------------------------------------------------
    */

    Route::get('/auth/{provider}/redirect', [AuthController::class, 'redirectToProvider'])->name('auth-redirectToProvider')->middleware(['throttle:loginLimit']);
    Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('auth-handleProviderCallback')->withoutMiddleware('throttle:defaultLimit');

    /*
    |-------------------------------------------------------------------------------------------------------
    | Endpointy dostępne po uwierzytelnieniu użytkownika
    |-------------------------------------------------------------------------------------------------------
    */

    Route::post('/email/verification-notification', [AuthController::class, 'sendVerificationEmail'])->name('auth-sendVerificationEmail');

    Route::delete('/auth/logout', [AuthController::class, 'logout'])->name('auth-logout')->withoutMiddleware('throttle:defaultLimit');
    Route::delete('/auth/logout-other-devices', [AuthController::class, 'logoutOtherDevices'])->name('auth-logoutOtherDevices')->middleware(['throttle:logoutOtherDevicesLimit']);

    Route::get('/user', [AuthController::class, 'getUser'])->name('auth-getUser');
    Route::delete('/user/avatar/delete', [AuthController::class, 'deleteAvatar'])->name('auth-deleteAvatar');

    /*
    |-------------------------------------------------------------------------------------------------------
    | Endpointy dostępne po poprawnej autoryzacji użytkownika
    |-------------------------------------------------------------------------------------------------------
    */

    Route::middleware('user.roles')->group(function () {

        /*
        |---------------------------------------------------------------------------------------------------
        | Endpointy dostępne wyłącznie ze zweryfikowanym mailem użytkownika
        |---------------------------------------------------------------------------------------------------
        */

        Route::middleware('verified')->group(function () {
            // TODO
        });
    });
});

/*
|-----------------------------------------------------------------------------------------------------------
| Endpointy do odbierania informacji z serwisu GitHub
|-----------------------------------------------------------------------------------------------------------
*/

Route::middleware('throttle:githubLimit')->group(function () {
    Route::post('/github/pull', [GitHubController::class, 'pull'])->name('github-pull');
});
