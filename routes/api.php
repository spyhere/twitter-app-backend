<?php

use App\Enums\TokenTypes;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\PasswordController;

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

Route::middleware(['auth:sanctum', 'verified'])->group(function() {
    Route::apiResource('posts', PostController::class)->except('index');

    Route::prefix('users')->group(function () {
        Route::get('', [ProfileController::class, 'index']);
        Route::get('{user}/posts', [PostController::class, 'getUserPosts']);

        Route::prefix('profile')->group(function() {
            Route::get('', [ProfileController::class, 'profile'])->withoutMiddleware('verified');
            Route::post('avatar', [ProfileController::class, 'storeAvatar']);
            Route::delete('avatar', [ProfileController::class, 'destroyAvatar']);
        });
    });
});

Route::prefix('auth')->group(function() {
    Route::post('login', [AuthController::class, 'logIn']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('logout', [AuthController::class, 'logOut'])->middleware('auth:sanctum');
});
Route::prefix('email')->group(function() {
    Route::post('verify/{token}', [VerificationController::class, 'verify'])->middleware(['decrypt:' . TokenTypes::EMAIL_VERIFY, 'email.not-verified']);
    Route::post('resend/', [VerificationController::class, 'resend'])->middleware(['auth:sanctum', 'email.not-verified']);
});
Route::prefix('reset-password')->group(function() {
    Route::post('', [PasswordController::class, 'requestPasswordChange'])->middleware(['auth:sanctum', 'verified']);
    Route::post('update/{token}', [PasswordController::class, 'update'])->middleware('decrypt:' . TokenTypes::PASSWORD_RESET);
});
Route::prefix('forgot-password')->group(function() {
    Route::post('', [PasswordController::class, 'forgotPassword']);
    Route::post('recover/{token}', [PasswordController::class, 'recover'])->middleware('decrypt:' . TokenTypes::PASSWORD_RECOVER);
});
