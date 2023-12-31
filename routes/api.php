<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Movie\GenreController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\Movie\MovieController;
use App\Http\Controllers\NotificationController;
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

Route::get('/change-locale/{locale}', [LanguageController::class, 'changeLocale'])->name('locale.change');

Route::controller(AuthController::class)->group(function () {
	Route::prefix('users')->group(function () {
		Route::post('/register', 'register')->name('auth.register');
		Route::post('/email-verification', 'verifyEmail')->name('auth.verification_date');
		Route::post('/resend-verification-email', 'resendVerificationEmail')->name('auth.resend_email');
		Route::post('/login', 'login')->name('auth.login');
	});
});

Route::post('reset-password/email', [ForgotPasswordController::class, 'sendVerificationEmail'])->name('reset.email');
Route::post('reset-password/change', [ForgotPasswordController::class, 'changePassword'])->name('reset.new_password');

Route::middleware(['auth:sanctum'])->group(function () {
	Route::post('logout', [AuthController::class, 'logout'])->name('logout');
	Route::controller(UserController::class)->group(function () {
		Route::get('users/user', 'getAuthenticatedUser')->name('auth.user');
		Route::post('edit/user/{user}', 'editUserInfo')->name('user.edit');
		Route::post('change-email', 'changeUserEmail')->name('user.change_email');
	});
	Route::controller(MovieController::class)->group(function () {
		Route::get('movies', 'index')->name('movies');
		Route::post('movie', 'store')->name('movie.create');
		Route::get('user/{user}/movies', 'getUserMovies')->name('user_movies.all');
		Route::get('movies/{movie}', 'show')->name('movie.get');
		Route::post('movies/{movie}', 'update')->name('movie.edit')->middleware('can:update,movie');
		Route::delete('movies/{movie}', 'destroy')->name('movie.delete')->middleware('can:destroy,movie');
	});
	Route::controller(QuoteController::class)->group(function () {
		Route::get('quotes', 'index')->name('quotes.all');
		Route::post('quote', 'store')->name('quote.create');
		Route::get('quotes/{quote}', 'show')->name('quote.get');
		Route::post('quotes/{quote}', 'update')->name('quote.edit')->middleware('can:update,quote');
		Route::delete('quotes/{quote}', 'destroy')->name('quote.delete')->middleware('can:destroy,quote');
	});
	Route::controller(NotificationController::class)->group(function () {
		Route::get('notifications', 'index')->name('notifications.all');
		Route::get('notifications/{notification}', 'makeNotificationRead')->name('notifications.read');
		Route::get('notifications-read-all', 'readAllNotifications')->name('notifications.read_all');
	});
	Route::post('like', [LikeController::class, 'like'])->name('quote.like');
	Route::post('comment', [CommentController::class, 'store'])->name('comment.add');
	Route::get('comments', [CommentController::class, 'index'])->name('comments');
	Route::get('genres', [GenreController::class, 'index'])->name('genre.all');
});

Route::get('google/auth', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('auth/google/call-back', [GoogleAuthController::class, 'callbackGoogle'])->name('google.callback')->middleware('web');
