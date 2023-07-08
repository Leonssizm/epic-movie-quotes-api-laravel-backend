<?php

namespace App\Policies;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MoviePolicy
{
	/**
	 * Create a new policy instance.
	 */
	public function update(User $user, Movie $movie)
	{
		return $user->id === $movie->user_id ? Response::allow() : Response::denyWithStatus(403);
	}

	public function destroy(User $user, Movie $movie)
	{
		return $user->id === $movie->user_id ? Response::allow() : Response::denyWithStatus(403);
	}
}
