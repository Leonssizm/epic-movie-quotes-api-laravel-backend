<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Mail\ChangeEmailMail;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
	public function getAuthenticatedUser(): JsonResponse
	{
		return response()->json(new UserResource(Auth::user()), 200);
	}

	public function editUserInfo(Request $request, User $user): JsonResponse
	{
		try {
			if ($request->has('profile_picture')) {
				if ($user->profile_picture) {
					File::delete('storage/' . $user->profile_picture);
				}

				$request->profile_picture = $this->storeImage($request);

				$user->update([
					'profile_picture' => $request->profile_picture,
				]);
			}

			if ($request->has('new_password')) {
				$user->update([
					'password' => $request->new_password,
				]);
			}

			if ($request->has('new_username')) {
				$user->update([
					'username' => $request->new_username,
				]);
			}

			if ($request->has('new_email')) {
				$token = Str::random(60);

				$user->email_verification_token = $token;
				$user->email_verification_token_created_at = now();

				$user->save();

				$verificationUrl = url(env('USER_VERIFICATION_NEW_EMAIL_TOKEN_LINK') . $token . '&email=' . $request->new_email);

				Mail::to($request->new_email)->send(new ChangeEmailMail($verificationUrl, $request->new_email));
			}

			return response()->json(['message' => 'User information updated successfully'], 200);
		} catch (\Exception $e) {
			return response()->json(['error' => 'An error occurred while updating user information'], 500);
		}
	}

	public function changeUserEmail(Request $request): JsonResponse
	{
		$user = User::firstWhere('email_verification_token', $request->token);

		if ($user) {
			$user->email = $request->email;
			$user->save();
			return response()->json('email is changed', 200);
		} else {
			return response()->json('something went wrong', 404);
		}
	}

	private function storeImage($request)
	{
		$storedImage = uniqid() . '-' . $request->username . '.' . $request->profile_picture->extension();
		$request->profile_picture->move('storage', $storedImage);
		return $storedImage;
	}
}
