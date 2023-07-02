<?php

namespace App\Http\Controllers;

use App\Events\NotificationSent;
use App\Http\Requests\Like\LikeRequest;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
	public function like(LikeRequest $request): JsonResponse
	{
		$validated = $request->validated();

		$alreadyLiked = Like::firstWhere('quote_id', $validated['quote_id']);

		$author = Quote::firstWhere('id', $validated['quote_id'])->user;

		if ($alreadyLiked) {
			if ($author->id !== auth()->id()) {
				$notification = Notification::firstWhere('quote_id', $alreadyLiked->quote_id);
				$alreadyLiked->delete();
				$notification->delete();
			} else {
				$alreadyLiked->delete();
			}

			return response()->json('unlike', 200);
		}

		Like::create([
			'quote_id' => $validated['quote_id'],
			'user_id'  => $validated['user_id'],
		]);

		if ($author->id !== auth()->id()) {
			$notification = Notification::create([
				'receiver_id'     => $author->id,
				'quote_id'        => $validated['quote_id'],
				'sender_id'       => auth()->id,
				'is_like'         => true,
			]);
			$quote = Quote::firstWhere('id', $validated['quote_id']);
			$sender = auth()->user();

			NotificationSent::dispatch(['user' => $author, 'quote' => $quote, 'sender'=>$sender, 'notification' => $notification]);
		}

		return response()->json('liked', 200);
	}
}
