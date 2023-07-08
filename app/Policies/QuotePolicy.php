<?php

namespace App\Policies;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuotePolicy
{
	/**
	 * Create a new policy instance.
	 */
	public function update(User $user, Quote $quote)
	{
		return $user->id === $quote->user_id ? Response::allow() : Response::denyWithStatus(403);
	}

	public function destroy(User $user, Quote $quote)
	{
		return $user->id === $quote->user_id ? Response::allow() : Response::denyWithStatus(403);
	}
}
