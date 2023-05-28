<?php

namespace App\Http\Controllers;

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

		$verificationUrl = url("http://localhost:5173/verify?token={$token}");

		Mail::to($user->email)->send(new VerifyUserEmail($verificationUrl, $user));
	}

	public function setEmailVerificationDate(Request $request): JsonResponse
	{
		$user = User::all()->where('email_verification_token', $request->token)->first();

		if (Carbon::now()->diffInMinutes($user->email_verification_token_created_at) >= 20 && empty($user->email_verified_at)) {
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

		if (Auth::attempt($request->validated())) {
			$token = $user->createToken('auth_token')->plainTextToken;
			return response()->json([
				'user' => $user,
				'token'=> $token,
			], 201);
		} else {
			return response()->json(401);
		}
	}

// For testing
	public function test()
	{
		return 'IF YOU ARE SEEING THIS AUTH IS CORRECT';
	}

	public function logout(Request $request)
	{
		return $request;
		// auth()->user()->tokens()->delete();
		// return response()->json([
		// 	'message' => 'Logged out',
		// ], 200);
	}
}
