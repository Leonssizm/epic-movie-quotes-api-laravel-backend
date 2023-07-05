<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		$this->loadMissing(['genres', 'quotes.likes', 'quotes.comments']);

		return [
			'id'                 => $this->id,
			'title'              => [
				'en'=> $this->getTranslation('title', 'en'),
				'ka'=> $this->getTranslation('title', 'ka'),
			],
			'release_year'          => $this->release_year,
			'director'              => [
				'en'=> $this->getTranslation('director', 'en'),
				'ka'=> $this->getTranslation('director', 'ka'),
			],
			'description'              => [
				'en'=> $this->getTranslation('description', 'en'),
				'ka'=> $this->getTranslation('description', 'ka'),
			],
			'user_id'           => $this->user_id,
			'image'             => $this->image,
			'created_at'        => $this->created_at,
			'updated_at'        => $this->updated_at,
			'quotes'            => $this->whenLoaded('quotes', function () {
				return QuoteResource::collection($this->quotes)->additional([
					'likes' => $this->quotes->pluck('likedByUsers')->flatten(),
				]);
			}),
			'genres'            => new GenreResource($this->whenLoaded('genres')),
		];
	}
}
