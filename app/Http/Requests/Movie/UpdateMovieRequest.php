<?php

namespace App\Http\Requests\Movie;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'title'       => 'required',
			'description' => 'required',
			'director'    => 'required',
			'genre_ids'   => 'required|array',
			'release_year'=> 'required',
		];
	}

	protected function prepareForValidation()
	{
		$this->merge([
			'title' => [
				'en' => $this->title_en,
				'ka' => $this->title_ka,
			],
			'description' => [
				'en' => $this->description_en,
				'ka' => $this->description_ka,
			],
			'director' => [
				'en' => $this->director_en,
				'ka' => $this->director_ka,
			],
		]);
	}
}
