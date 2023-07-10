<?php

namespace App\Broadcasting;

use App\Models\User;

class NotificationsChannel
{
	/**
	 * Create a new channel instance.
	 */
	public function __construct()
	{
	}

	/**
	 * Authenticate the user's access to the channel.
	 */
	public function join($connection, $userId)
	{
		return $userId == auth()->user()->id;
	}
}
