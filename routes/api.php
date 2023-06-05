<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\GoogleAuthController;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
// 	return $request;
// });

Route::post('users/create', [AuthController::class, 'register'])->name('auth.register');
Route::post('users/email-verification', [AuthController::class, 'verifyEmail'])->name('auth.verificationDate');
Route::post('users/resend-verification-email', [AuthController::class, 'resendVerificationEmail'])->name('auth.resendEmail');
Route::post('users/login', [AuthController::class, 'login'])->name('auth.login');

// forgot password

Route::post('reset-password/email', [ForgotPasswordController::class, 'sendVerificationEmail'])->name('reset.email');
Route::post('reset-password/change', [ForgotPasswordController::class, 'changePassword'])->name('reset.newPassword');

Route::middleware(['auth:sanctum'])->group(function () {
	// For testing purposes
	Route::get('newsfeed', [AuthController::class, 'test'])->name('test');
	Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('google/auth', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('auth/google/call-back', [GoogleAuthController::class, 'callbackGoogle'])->name('google.callback')->middleware('web');
