<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignUpRequest;
use App\Mail\VerifyUserEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
	public function register(SignUpRequest $request): JsonResponse
	{
		$user = User::create($request->validated());

		$this->sendVerificationEmail($user);

		return response()->json([$user], 201);
	}

	protected function sendVerificationEmail($user): void
	{
		$token = Str::random(60);

		$user->email_verification_token = $token;
		$user->email_verification_token_created_at = now();
		$user->save();

		$verificationUrl = url(env('FRONTEND_APP_URL') . "verify?token={$token}");

		Mail::to($user->email)->send(new VerifyUserEmail($verificationUrl, $user));
	}

	public function verifyEmail(Request $request): JsonResponse
	{
		$user = User::all()->where('email_verification_token', $request->token)->first();

		if (Carbon::now()->diffInMinutes($user->email_verification_token_created_at) >= 10 && empty($user->email_verified_at)) {
			return response()->json([
				'message' => 'Token expired',
				'expired' => true,
			], 410);
		} else {
			$user->markEmailAsVerified();
		}

		return response()->json($user);
	}

	public function resendVerificationEmail(Request $request): JsonResponse
	{
		$user = User::all()->where('email_verification_token', $request->token)->first();

		$user->email_verification_token = null;
		$user->email_verification_token_created_at = null;
		$user->save();

		$this->sendVerificationEmail($user);

		return response()->json([$user], 201);
	}

	public function login(LoginRequest $request): JsonResponse
	{
		$user = User::where('email', $request->email)->first();

		$validatedRequest = $request->validated();

		if (Auth::attempt($validatedRequest, $request->filled('rememberMe')) && $user->email_verified_at !== null) {
			return response()->json([
				'user'    => $user,
			], 201);
		}
	}

	public function logout(): JsonResponse
	{
		Auth::guard('web')->logout();
		return response()->json([
			'message' => 'Logged out',
		], 200);
	}
}
