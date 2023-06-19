<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationSent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $notification;

	/**
	 * Create a new event instance.
	 */
	public function __construct($notification)
	{
		$this->notification = $notification;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return Channel|array \Illuminate\Broadcasting\Channel>
	 */
	public function broadcastOn()
	{
		return new PrivateChannel('movie-quotes.' . $this->notification['user']->id);
	}
}
