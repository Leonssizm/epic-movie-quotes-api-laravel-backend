<?php

use App\Http\Controllers\AuthController;
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
Route::post('users/email-verification', [AuthController::class, 'setEmailVerificationDate'])->name('auth.verificationDate');
Route::post('users/resend-verification-email', [AuthController::class, 'resendVerificationEmail'])->name('auth.resendEmail');
Route::post('users/login', [AuthController::class, 'login'])->name('auth.login');

Route::middleware(['auth:sanctum'])->group(function () {
	// For testing purposes
	Route::get('newsfeed', [AuthController::class, 'test'])->name('test');
	Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
