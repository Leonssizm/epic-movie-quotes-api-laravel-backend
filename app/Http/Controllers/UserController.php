<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
	public function getAuthenticatedUser(): JsonResponse
	{
		return response()->json(Auth::user(), 200);
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

			return response()->json(['message' => 'User information updated successfully'], 200);
		} catch (\Exception $e) {
			return response()->json(['error' => 'An error occurred while updating user information'], 500);
		}
	}

	private function storeImage($request)
	{
		$storedImage = uniqid() . '-' . $request->username . '.' . $request->profile_picture->extension();
		$request->profile_picture->move('storage', $storedImage);
		return $storedImage;
	}
}
