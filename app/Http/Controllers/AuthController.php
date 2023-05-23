<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignUpRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
	public function register(SignUpRequest $request): JsonResponse
	{
		$user = User::create($request->validated());

		return response()->json($user, 201);
	}
}
