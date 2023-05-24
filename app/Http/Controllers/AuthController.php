<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignUpRequest;
use App\Mail\VerifyUserEmail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
		$user->save();

		$verificationUrl = url("http://localhost:5173/verify?token={$token}");

		Mail::to($user->email)->send(new VerifyUserEmail($verificationUrl, $user));
	}

	public function setVerificationDate(Request $request): JsonResponse
	{
		$user = User::all()->where('email_verification_token', $request->token)->first();
		$user->email_verified_at = now();
		$user->save();
		return response()->json($user);
	}
}
