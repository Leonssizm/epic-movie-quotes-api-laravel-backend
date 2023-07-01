<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		$this->loadMissing(['user', 'comments.user', 'likes']);
		return [
			'id'                => $this->id,
			'body'              => [
				'en'=> $this->getTranslation('body', 'en'),
				'ka'=> $this->getTranslation('body', 'ka'),
			],
			'movie_id'          => $this->movie_id,
			'user_id'           => $this->user_id,
			'thumbnail'         => $this->thumbnail,
			'created_at'        => $this->created_at,
			'updated_at'        => $this->updated_at,
			'movie'             => new MovieResource($this->whenLoaded('movie')),
			'user'              => new UserResource($this->whenLoaded('user')),
			'comments'          => CommentResource::collection($this->whenLoaded('comments')),
			'likes'             => LikeResource::collection($this->whenLoaded('likes')),
		];
	}
}
