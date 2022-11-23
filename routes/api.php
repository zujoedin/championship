<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\ConfigController;
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

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [LoginController::class, 'logout']);

    Route::get('user', [UserController::class, 'current']);

    Route::patch('settings/profile', [ProfileController::class, 'update']);
    Route::patch('settings/password', [PasswordController::class, 'update']);

});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);

    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);

    Route::post('email/verify/{user}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend']);

    Route::post('oauth/{driver}', [OAuthController::class, 'redirect']);
    Route::get('oauth/{driver}/callback', [OAuthController::class, 'handleCallback'])->name('oauth.callback');
});



Route::post('/get-teams', [ConfigController::class, 'getTeams']);
Route::post('/get-mortals', [ConfigController::class, 'getMortals']);
Route::post('/get-pairs', [ConfigController::class, 'getPairs']);
Route::post('/get-results', [ConfigController::class, 'getResults']);
Route::post('/get-table', [ConfigController::class, 'getTable']);
Route::post('/get-table-teams', [ConfigController::class, 'getTableTeams']);

Route::post('/post-pair', [ConfigController::class, 'postPair']);
Route::post('/post-mortal', [ConfigController::class, 'postMortal']);
Route::post('/post-team', [ConfigController::class, 'postTeam']);
Route::post('/post-result', [ConfigController::class, 'postResult']);

Route::post('/add-additional-score', [ConfigController::class, 'addAdditionalScore']);
Route::post('/reduce-additional-score', [ConfigController::class, 'reduceAdditionalScore']);


Route::post('/delete-team', [ConfigController::class, 'deleteTeam']);
Route::post('/delete-mortal', [ConfigController::class, 'deleteMortal']);
Route::post('/delete-result', [ConfigController::class, 'deleteResult']);
Route::post('/delete-pair', [ConfigController::class, 'deletePair']);