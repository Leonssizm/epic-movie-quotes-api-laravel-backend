<?php

namespace App\Http\Controllers;

use App\Events\NotificationSent;
use App\Http\Requests\Like\LikeRequest;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;

class LikeController extends Controller
{
	public function like(LikeRequest $request)
	{
		$validated = $request->validated();

		$alreadyLiked = Like::firstWhere('user_id', $validated['user_id']);

		if ($alreadyLiked) {
			$alreadyLiked->delete();
			return response()->json('unlike', 200);
		}

		Like::create([
			'quote_id' => $validated['quote_id'],
			'user_id'  => $validated['user_id'],
		]);

		$author = Quote::firstWhere('id', $validated['quote_id'])->user;

		$notification = Notification::create([
			'receiver_id'     => $author->id,
			'quote_id'        => $validated['quote_id'],
			'sender_id'       => auth()->user()->id,
			'is_like'         => true,
		]);
		$quote = Quote::firstWhere('id', $validated['quote_id']);
		$sender = User::firstWhere('id', auth()->user()->id);

		NotificationSent::dispatch(['user' => $author, 'quote' => $quote, 'sender'=>$sender, 'notification' => $notification]);

		return response()->json('liked', 200);
	}
}
