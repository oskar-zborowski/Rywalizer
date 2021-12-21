<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DefaultTypeController;
use App\Http\Controllers\Api\GitHubController;
use App\Http\Controllers\Api\UserController;
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

        Route::get('/provider/types', [DefaultTypeController::class, 'getProviderTypes'])->name('defaultType-getProviderTypes');

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

            Route::patch('/user', [UserController::class, 'updateUser'])->name('user-updateUser');
            Route::patch('/user/email/verify', [UserController::class, 'verifyEmail'])->name('user-verifyEmail');
            Route::post('/user/avatar/upload', [UserController::class, 'uploadAvatar'])->name('user-uploadAvatar');
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

            Route::get('/user', [UserController::class, 'getUser'])->name('user-getUser');
            Route::post('/user/email/verification-notification', [UserController::class, 'sendVerificationEmail'])->name('user-sendVerificationEmail');
            Route::delete('/user/avatar/delete', [UserController::class, 'deleteAvatar'])->name('user-deleteAvatar');
            Route::get('/user/{id}/authentication', [UserController::class, 'getUserAuthentication'])->name('user-getUserAuthentication');

            Route::get('/gender/types', [DefaultTypeController::class, 'getGenderTypes'])->name('defaultType-getGenderTypes');

            /*
            |---------------------------------------------------------------------------------------------------
            | Endpointy dostępne wyłącznie ze zweryfikowanym mailem użytkownika
            |---------------------------------------------------------------------------------------------------
            */

            Route::middleware('verified')->group(function () {

                Route::get('/role/types', [DefaultTypeController::class, 'getRoleTypes'])->name('defaultType-getRoleTypes');
                Route::get('/account-action/types', [DefaultTypeController::class, 'getAccountActionTypes'])->name('defaultType-getAccountActionTypes');

                Route::get('/users', [UserController::class, 'getUsers'])->name('user-getUsers');
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
