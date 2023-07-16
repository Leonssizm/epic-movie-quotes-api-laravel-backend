<?php

namespace App\Http\Controllers;

use App\Events\NotificationSent;
use App\Http\Requests\Like\LikeRequest;
use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
	public function like(LikeRequest $request): JsonResponse
	{
		$validated = $request->validated();
		$quote = Quote::find($validated['quote_id'])->load('likes');
		$author = $quote->user;

		$alreadyLiked = $quote->likes->firstWhere('id', auth()->id());

		if ($alreadyLiked) {
			$alreadyLiked->pivot->delete();

			Notification::where('receiver_id', $author->id)->where('sender_id', $validated['user_id'])->where('is_like', true)->delete();

			NotificationSent::dispatch(['user' => $author, 'quote' => $quote, 'unlike' => true]);

			return response()->json('unlike', 200);
		}

		$quote->likedByUsers()->attach($validated['user_id']);

		if ($author->id !== auth()->id()) {
			$notification = Notification::create([
				'receiver_id'     => $author->id,
				'notifiable_id'   => $validated['quote_id'],
				'notifiable_type' => Quote::class,
				'sender_id'       => auth()->id(),
				'is_like'         => true,
				'is_new'          => true,
			]);

			$sender = auth()->user();

			NotificationSent::dispatch(['user' => $author, 'quote' => $quote, 'sender' => $sender, 'notification' => $notification]);
		}

		return response()->json('liked', 200);
	}
}
