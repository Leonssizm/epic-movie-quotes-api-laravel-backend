<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class QuoteCollection extends ResourceCollection
{
	/*
	 * Transform the resource collection into an array.
	 *
	 * @return array<int|string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return $this->collection->map(function ($quote) {
			return [
				'id'                => $quote->id,
				'body'              => [
					'en'=> $quote->getTranslation('body', 'en'),
					'ka'=> $quote->getTranslation('body', 'ka'),
				],
				'thumbnail'=> $quote->thumbnail,
				'movie'    => new MovieResource($quote->movie),
				'user'     => new UserResource($quote->user),
				'comments' => CommentResource::collection($quote->comments),
				'likes'    => LikeResource::collection($quote->likes),
			];
		})->toArray();
	}
}
