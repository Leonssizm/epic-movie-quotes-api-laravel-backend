<?php

namespace App\Http\Requests\Quote;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'body'               => 'required',
			'movie_id'           => 'required',
			'user_id'            => 'required',
			'thumbnail'          => 'required|file',
		];
	}

	protected function prepareForValidation()
	{
		$this->merge([
			'body'  => [
				'en'=> $this->body_en,
				'ka'=> $this->body_ka,
			],
		]);
	}
}
