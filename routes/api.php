<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GitHubController;
use Illuminate\Support\Facades\Route;

/*
|---------------------------------------------------------------------------------------------------------------
| API Routes
|---------------------------------------------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('throttle:defaultLimit')->group(function () {

    /*
    |-----------------------------------------------------------------------------------------------------------
    | Endpointy sprawdzane przez middleware'a Authenticate.php -
    | w przypadku nowych pozycji należy uzupełnić powyższy middleware
    |-----------------------------------------------------------------------------------------------------------
    */

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/provider/types', [AuthController::class, 'getProviderTypes'])->name('auth-getProviderTypes');

        /*
        |-------------------------------------------------------------------------------------------------------
        | Endpointy wpływające na encję użytkownika i wymagające dodatkowego sprawdzenia poprawności danych -
        | w przypadku nowych pozycji należy uzupełnić middleware'a BeforeUser.php
        |-------------------------------------------------------------------------------------------------------
        */

        Route::middleware('before.user')->group(function () {

            Route::post('/auth/login', [AuthController::class, 'login'])->name('auth-login')->middleware(['throttle:loginLimit']);
            Route::post('/auth/register', [AuthController::class, 'register'])->name('auth-register')->middleware(['throttle:registerLimit']);

            Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword'])->name('auth-forgotPassword');
            Route::patch('/auth/reset-password', [AuthController::class, 'resetPassword'])->name('auth-resetPassword');

            Route::delete('/auth/logout/other-devices', [AuthController::class, 'logoutOtherDevices'])->name('auth-logoutOtherDevices');

            Route::patch('/user', [AuthController::class, 'updateUser'])->name('auth-updateUser');
            Route::patch('/user/email/verify', [AuthController::class, 'verifyEmail'])->name('auth-verifyEmail');
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
        | Endpointy dostępne po poprawnym uwierzytelnieniu i autoryzacji użytkownika
        |-------------------------------------------------------------------------------------------------------
        */

        Route::delete('/auth/logout/me', [AuthController::class, 'logoutMe'])->name('auth-logoutMe')->withoutMiddleware('throttle:defaultLimit');

        Route::middleware('user.roles')->group(function () {

            Route::get('/user', [AuthController::class, 'getUser'])->name('auth-getUser');
            Route::post('/user/email/verification-notification', [AuthController::class, 'sendVerificationEmail'])->name('auth-sendVerificationEmail');
            Route::delete('/user/avatar/delete', [AuthController::class, 'deleteAvatar'])->name('auth-deleteAvatar');
            Route::get('/user/{id}/authentication', [AuthController::class, 'getUserAuthentication'])->name('auth-getUserAuthentication');

            Route::get('/gender/types', [AuthController::class, 'getGenderTypes'])->name('auth-getGenderTypes');

            /*
            |---------------------------------------------------------------------------------------------------
            | Endpointy dostępne wyłącznie ze zweryfikowanym mailem użytkownika
            |---------------------------------------------------------------------------------------------------
            */

            Route::middleware('verified')->group(function () {

                Route::get('/role/types', [AuthController::class, 'getRoleTypes'])->name('auth-getRoleTypes');
                Route::get('/account-action/types', [AuthController::class, 'getAccountActionTypes'])->name('auth-getAccountActionTypes');

                Route::get('/users', [AuthController::class, 'getUsers'])->name('auth-getUsers');
            });
        });
    });
});

/*
|---------------------------------------------------------------------------------------------------------------
| Endpointy do odbierania informacji z serwisu GitHub
|---------------------------------------------------------------------------------------------------------------
*/

Route::middleware('throttle:githubLimit')->group(function () {
    Route::post('/github/pull', [GitHubController::class, 'pull'])->name('github-pull');
});
