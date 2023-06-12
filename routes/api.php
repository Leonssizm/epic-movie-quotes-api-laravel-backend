<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\UserController;
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

Route::get('/change.locale/{locale}', [LanguageController::class, 'changeLocale'])->name('locale.change');

Route::controller(AuthController::class)->group(function () {
	Route::prefix('users')->group(function () {
		Route::post('/register', 'register')->name('auth.register');
		Route::post('/email-verification', 'verifyEmail')->name('auth.verificationDate');
		Route::post('/resend-verification-email', 'resendVerificationEmail')->name('auth.resendEmail');
		Route::post('/login', 'login')->name('auth.login');
	});
});

// forgot password

Route::post('reset-password/email', [ForgotPasswordController::class, 'sendVerificationEmail'])->name('reset.email');
Route::post('reset-password/change', [ForgotPasswordController::class, 'changePassword'])->name('reset.newPassword');

Route::middleware(['auth:sanctum'])->group(function () {
	Route::post('logout', [AuthController::class, 'logout'])->name('logout');
	Route::get('users/user', [UserController::class, 'getAuthenticatedUser'])->name('auth.user');
	Route::get('movies', [MovieController::class, 'index'])->name('movies.all');
	Route::get('quotes', [QuoteController::class, 'index'])->name('quotes.all');
});

Route::get('google/auth', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('auth/google/call-back', [GoogleAuthController::class, 'callbackGoogle'])->name('google.callback')->middleware('web');
