<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignUpRequest;
use App\Mail\VerifyUserEmail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
	public function register(SignUpRequest $request): JsonResponse
	{
		$user = User::create($request->validated());

		$this->sendVerificationEmail($user);

		return response()->json(201);
	}

	protected function sendVerificationEmail($user): void
	{
		$token = $user->createToken('Email Verification Token')->plainTextToken;

		$verificationUrl = url("http://localhost:5173/verify?token={$token}");

		Mail::to($user->email)->send(new VerifyUserEmail($verificationUrl, $user));
	}
}
