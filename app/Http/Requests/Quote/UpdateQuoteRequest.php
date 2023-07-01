<?php

namespace App\Http\Requests\Quote;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuoteRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'body'=> 'required',
		];
	}

	protected function prepareForValidation()
	{
		$this->merge([
			'body' => [
				'en' => $this->quote_en,
				'ka' => $this->quote_ka,
			],
		]);
	}
}
