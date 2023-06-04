<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
	public function redirect(): JsonResponse
	{
		$redirectUrl = Socialite::driver('google')->redirect()->getTargetUrl();
		return response()->json(['redirectUrl' => $redirectUrl], 200);
	}

	public function callbackGoogle(): RedirectResponse | JsonResponse
	{
		try {
			$google_user = Socialite::driver('google')->stateless()->user();

			$user = User::where('google_id', $google_user->getId())->first();

			if (!$user) {
				$new_user = User::create([
					'username'                => $google_user->getName(),
					'email'                   => $google_user->getEmail(),
					'google_id'               => $google_user->getId(),
					'password'                => Str::random(10),
					'email_verification_token'=> Str::random(60),
					'email_verified_at'       => now(),
				]);

				return redirect()->away('http://localhost:5173/auth/google/call-back/' . $new_user->google_id);
			} else {
				return response()->json('User already exists', 401);
			}
		} catch(\Throwable $th) {
			return response()->json(['Something went wrong', $th], 404);
		}
	}
}
