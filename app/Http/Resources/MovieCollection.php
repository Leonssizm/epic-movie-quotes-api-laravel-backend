<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MovieCollection extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @return array<int|string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return $this->collection->map(function ($movie) {
			return [
				'id'                 => $movie->id,
				'title'              => [
					'en'=> $movie->getTranslation('title', 'en'),
					'ka'=> $movie->getTranslation('title', 'ka'),
				],
				'release_year'          => $movie->release_year,
				'director'              => [
					'en'=> $movie->getTranslation('director', 'en'),
					'ka'=> $movie->getTranslation('director', 'ka'),
				],
				'description'              => [
					'en'=> $movie->getTranslation('description', 'en'),
					'ka'=> $movie->getTranslation('description', 'ka'),
				],
				'user_id'           => $movie->user_id,
				'image'             => $movie->image,
				'created_at'        => $movie->created_at,
				'updated_at'        => $movie->updated_at,
				'quotes'            => QuoteResource::collection($movie->quotes),
			];
		})->toArray();
	}
}
