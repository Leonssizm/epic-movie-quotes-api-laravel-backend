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

			$new_user = User::firstOrCreate(
				['google_id' => $google_user->getId()],
				[
					'username'                => $google_user->getName(),
					'email'                   => $google_user->getEmail(),
					'password'                => Str::random(10),
					'email_verification_token'=> Str::random(60),
					'email_verified_at'       => now(),
				]
			);

			if ($new_user->wasRecentlyCreated && !$user) {
				auth()->login($new_user);

				return redirect()->away('http://localhost:5173/auth/google/call-back/' . $new_user->google_id);
			}

			if (!$new_user->wasRecentlyCreated && $user) {
				auth()->login($user);

				return redirect()->away('http://localhost:5173/home');
			}
		} catch(\Throwable $th) {
			return response()->json(['Something went wrong', $th], 404);
		}
	}
}
