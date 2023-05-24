<?php

use App\Http\Controllers\AuthController;
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

Route::post('users/create', [AuthController::class, 'register'])->name('auth.register');
Route::post('users/email-verification-date', [AuthController::class, 'setVerificationDate'])->name('auth.verificationDate');
