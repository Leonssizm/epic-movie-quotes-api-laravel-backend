<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class LoginRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'email'    => 'required|exists:users,email',
			'password' => [
				'required',
				function ($attribute, $value, $fail) {
					$user = User::where('email', $this->input('email'))->first();
					if (!$user) {
						return;
					}
					if (!Hash::check($value, $user->password)) {
						$fail('Password is incorrect');
					}
				},
			],
		];
	}
}
