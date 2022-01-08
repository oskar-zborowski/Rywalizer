<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DefaultTypeController;
use App\Http\Controllers\Api\GitHubController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------------------------------
| Endpointy wpływające na encję użytkownika i wymagające dodatkowego sprawdzenia poprawności danych -
| w przypadku nowych pozycji należy uzupełnić middleware'a BeforeUser.php
|-------------------------------------------------------------------------------------------------------
*/

Route::middleware('before.user')->group(function () {

    Route::post('/v1/auth/login', [AuthController::class, 'login'])->name('auth-login')->middleware('throttle:loginLimit');
    Route::post('/v1/auth/register', [AuthController::class, 'register'])->name('auth-register')->middleware('throttle:registerLimit');

    Route::post('/v1/account/password', [AccountController::class, 'forgotPassword'])->name('account-forgotPassword');
    Route::put('/v1/account/password', [AccountController::class, 'resetPassword'])->name('account-resetPassword');
    Route::put('/v1/account/restore', [AccountController::class, 'restoreAccount'])->name('account-restoreAccount');

    Route::patch('/v1/user', [UserController::class, 'updateUser'])->name('user-updateUser');
    Route::put('/v1/user/email', [UserController::class, 'verifyEmail'])->name('user-verifyEmail');
    Route::post('/v1/user/avatar', [UserController::class, 'uploadAvatar'])->name('user-uploadAvatar');
    Route::put('/v1/user/avatar', [UserController::class, 'changeAvatar'])->name('user-changeAvatar');
    Route::post('/v1/user/image', [UserController::class, 'uploadImage'])->name('user-uploadImage');
});





/*
|-------------------------------------------------------------------------------------------------------
| Endpointy do zewnętrznego uwierzytelnienia
|-------------------------------------------------------------------------------------------------------
*/

Route::get('/v1/auth/{provider}/redirect', [AuthController::class, 'redirectToProvider'])->name('auth-redirectToProvider')->middleware('throttle:loginLimit');
Route::get('/v1/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('auth-handleProviderCallback')->withoutMiddleware('throttle:api');





/*
|-------------------------------------------------------------------------------------------------------
| Endpointy podlegające procesowi autoryzacji
|-------------------------------------------------------------------------------------------------------
*/

Route::delete('/v1/auth/logout', [AuthController::class, 'logout'])->name('auth-logout');
Route::delete('/v1/auth/logout/all', [AuthController::class, 'logoutAll'])->name('auth-logoutAll');

Route::get('/v1/user', [UserController::class, 'getUser'])->name('user-getUser');
Route::post('/v1/user/email', [UserController::class, 'sendVerificationEmail'])->name('user-sendVerificationEmail');
Route::delete('/v1/user/avatar', [UserController::class, 'deleteAvatar'])->name('user-deleteAvatar');
Route::delete('/v1/user/images', [UserController::class, 'deleteImages'])->name('user-deleteImages');
Route::get('/v1/user/authentications', [UserController::class, 'getUserAuthentications'])->name('user-getUserAuthentications');

Route::get('/v1/users', [UserController::class, 'getAllUsers'])->name('user-getAllUsers');
Route::get('/v1/users/{id}/authentications', [UserController::class, 'getUserAuthentications'])->name('user-getUserAuthentications');

Route::get('/v1/default-type-names', [DefaultTypeController::class, 'getDefaultTypeNames'])->name('defaultType-getDefaultTypeNames');
Route::get('/v1/default-types/{name}', [DefaultTypeController::class, 'getDefaultTypes'])->name('defaultType-getDefaultTypes');
Route::get('/v1/providers', [DefaultTypeController::class, 'getProviders'])->name('defaultType-getProviders');
Route::get('/v1/genders', [DefaultTypeController::class, 'getGenders'])->name('defaultType-getGenders');





/*
|---------------------------------------------------------------------------------------------------
| Endpointy dostępne wyłącznie ze zweryfikowanym mailem użytkownika
|---------------------------------------------------------------------------------------------------
*/

Route::middleware('verified')->group(function () {
});





/*
|---------------------------------------------------------------------------------------------------------------
| Endpointy do odbierania informacji z serwisu GitHub
|---------------------------------------------------------------------------------------------------------------
*/

Route::middleware('throttle:githubLimit')->group(function () {
    Route::post('/v1/github/pull', [GitHubController::class, 'pull'])->name('github-pull');
});
