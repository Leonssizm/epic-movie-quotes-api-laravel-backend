<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray($request): array
	{
		return [
			'id'                => $this->id,
			'body'              => $this->body,
			'quote_id'          => $this->quote_id,
			'user_id'           => $this->user_id,
			'created_at'        => $this->created_at,
			'updated_at'        => $this->updated_at,
			'user'              => new UserResource($this->user),
		];
	}
}
