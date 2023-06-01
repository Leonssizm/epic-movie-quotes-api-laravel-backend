<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
	public function redirect(): JsonResponse
	{
		$redirectUrl = Socialite::driver('google')->redirect()->getTargetUrl();
		return response()->json(['redirectUrl' => $redirectUrl], 200);
	}

	public function callbackGoogle()
	{
		return Socialite::driver('google')->user();
		try {
			$google_user = Socialite::driver('google')->user();

			$user = User::where('google_id', $google_user->getId())->first();

			if (!$user) {
				$new_user = User::create([
					'name'     => $google_user->getName(),
					'email'    => $google_user->getEmail(),
					'google_id'=> $google_user->getId(),
				]);
				return response()->json('user is created', 200);
			} else {
				return response()->json(['User already exists', $user], 200);
			}
		} catch(\Throwable $th) {
			return response()->json(['Something went wrong', $th], 404);
		}
	}
}
