<?php

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

    Route::post('/auth/login', [AuthController::class, 'login'])->name('auth-login')->middleware('throttle:loginLimit');
    Route::post('/auth/register', [AuthController::class, 'register'])->name('auth-register')->middleware('throttle:registerLimit');

    Route::post('/accounts/password', [AuthController::class, 'forgotPassword'])->name('auth-forgotPassword');
    Route::patch('/accounts/password', [AuthController::class, 'resetPassword'])->name('auth-resetPassword');
    Route::patch('/accounts/restore', [AuthController::class, 'restoreAccount'])->name('auth-restoreAccount');

    Route::patch('/users', [UserController::class, 'updateUser'])->name('user-updateUser');
    Route::patch('/users/email', [UserController::class, 'verifyEmail'])->name('user-verifyEmail');
    Route::post('/users/avatar', [UserController::class, 'uploadAvatar'])->name('user-uploadAvatar');
});





/*
|-------------------------------------------------------------------------------------------------------
| Endpointy do zewnętrznego uwierzytelnienia
|-------------------------------------------------------------------------------------------------------
*/

Route::get('/auth/{provider}/redirect', [AuthController::class, 'redirectToProvider'])->name('auth-redirectToProvider')->middleware('throttle:loginLimit');
Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('auth-handleProviderCallback')->withoutMiddleware('throttle:api');





/*
|-------------------------------------------------------------------------------------------------------
| Endpointy dostępne po poprawnym uwierzytelnieniu i autoryzacji użytkownika
|-------------------------------------------------------------------------------------------------------
*/

Route::delete('/auth/logout/me', [AuthController::class, 'logoutMe'])->name('auth-logoutMe');
Route::delete('/auth/logout/other-devices', [AuthController::class, 'logoutOtherDevices'])->name('auth-logoutOtherDevices');

Route::get('/users', [UserController::class, 'getUser'])->name('user-getUser');
Route::get('/users/{id}', [UserController::class, 'getUser'])->name('user-getUser');
Route::get('/users/all', [UserController::class, 'getAllUsers'])->name('user-getAllUsers');

Route::post('/users/email', [UserController::class, 'sendVerificationEmail'])->name('user-sendVerificationEmail');
Route::delete('/users/avatar', [UserController::class, 'deleteAvatar'])->name('user-deleteAvatar');

Route::get('/users/authentication', [UserController::class, 'getUserAuthentication'])->name('user-getUserAuthentication');
Route::get('/users/{id}/authentication', [UserController::class, 'getUserAuthentication'])->name('user-getUserAuthentication');

Route::get('/providers', [DefaultTypeController::class, 'getProviders'])->name('defaultType-getProviders');
Route::get('/genders', [DefaultTypeController::class, 'getGenders'])->name('defaultType-getGenders');
Route::get('/roles', [DefaultTypeController::class, 'getRoles'])->name('defaultType-getRoles');
Route::get('/account-action-types', [DefaultTypeController::class, 'getAccountActionTypes'])->name('defaultType-getAccountActionTypes');





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
    Route::post('/github/pull', [GitHubController::class, 'pull'])->name('github-pull');
});
