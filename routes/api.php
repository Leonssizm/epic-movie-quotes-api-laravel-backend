<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MovieController;
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
	Route::controller(UserController::class)->group(function () {
		Route::get('users/user', 'getAuthenticatedUser')->name('auth.user');
		Route::post('edit/user/{user}', 'editUserInfo')->name('user.edit');
		Route::post('change-email', 'changeUserEmail')->name('user.changeEmail');
	});
	Route::controller(MovieController::class)->group(function () {
		Route::get('movies', 'index')->name('movies');
		Route::post('create-movie', 'createMovie')->name('movie.create');
		Route::get('user/{user}/movies', 'getAllUserMovies')->name('userMovies.all');
		Route::get('movies/{movie}', 'getSingleMovie')->name('movie.get');
		Route::post('movies/edit/{movie}', 'editMovie')->name('movie.edit');
		Route::delete('movies/delete/{movie}', 'deleteMovie')->name('movie.delete');
	});
	Route::controller(QuoteController::class)->group(function () {
		Route::get('quotes', 'index')->name('quotes.all');
		Route::post('create-quote', 'createQuote')->name('quote.create');
		Route::get('quotes/{quote}', 'getSingleQuote')->name('quote.get');
		Route::post('quotes/edit/{quote}', 'editQuote')->name('quote.edit');
		Route::delete('quotes/delete/{quote}', 'deleteQuote')->name('quote.delete');
	});

	Route::post('like-quote', [LikeController::class, 'like'])->name('quote.like');
	Route::post('write-comment', [CommentController::class, 'addComment'])->name('comment.add');

	Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.all');
	Route::get('notifications/{notification}', [NotificationController::class, 'makeNotificationRead'])->name('notifications.read');

	Route::get('genres', [GenreController::class, 'index'])->name('genre.all');
});

Route::get('google/auth', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('auth/google/call-back', [GoogleAuthController::class, 'callbackGoogle'])->name('google.callback')->middleware('web');
