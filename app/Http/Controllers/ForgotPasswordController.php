<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
	public function sendVerificationEmail(Request $request): JsonResponse
	{
		$email = $request->validate(['email' => 'required|exists:users,email'])['email'];

		$user = User::all()->where('email', $email)->first();

		$this->sendResetPasswordEmail($user);

		return response()->json(200);
	}

	protected function sendResetPasswordEmail($user): void
	{
		$token = Str::random(60);

		$user->email_verification_token = $token;
		$user->email_verification_token_created_at = now();
		$user->save();

		$verificationUrl = url("http://localhost:5173/verify-password?token={$token}");

		Mail::to($user->email)->send(new ResetPasswordMail($verificationUrl, $user));
	}

	public function changePassword(ChangePasswordRequest $request, User $user): JsonResponse
	{
		$user = User::all()->where('email_verification_token', $request->token)->first();

		$user->update($request->validated());

		return response()->json(200);
	}
}
