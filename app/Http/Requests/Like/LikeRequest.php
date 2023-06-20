<?php

namespace App\Http\Requests\Like;

use Illuminate\Foundation\Http\FormRequest;

class LikeRequest extends FormRequest
{
	/**
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'user_id'  => 'required',
			'quote_id' => 'required',
		];
	}
}
