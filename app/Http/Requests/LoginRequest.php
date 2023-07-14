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
			'email'    => 'required|string',
			'password' => [
				'required',
				function ($attribute, $value, $fail) {
					$fieldType = $this->getFieldType($this->input('email'));

					if ($fieldType === 'email') {
						$user = User::where('email', $this->input('email'))->first();
						if (!$user) {
							$fail('Email is incorrect');
							return;
						}
						if (!Hash::check($value, $user->password)) {
							$fail('Password is incorrect');
						}
					} else {
						$user = User::where('username', $this->input('email'))->first();

						if (!$user) {
							$fail('Username is incorrect');
							return;
						}
						if (!Hash::check($value, $user->password)) {
							$fail('Password is incorrect');
						}
					}
				},
			],
		];
	}

	public function getFieldType($input): string
	{
		return filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
	}
}
