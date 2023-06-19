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
	public function like(LikeRequest $request, Like $like)
	{
		$validated = $request->validated();

		$alreadyLiked = $like->where('user_id', $validated['user_id'])->where('quote_id', $validated['quote_id'])->first();

		if ($alreadyLiked) {
			$alreadyLiked->delete();

			return response()->json('unlike', 200);
		}

		$like->create([
			'quote_id' => $validated['quote_id'],
			'user_id'  => $validated['user_id'],
		]);

		$author = Quote::where('id', $validated['quote_id'])->first()->user;

		$notification = Notification::create([
			'receiver_id'     => $author->id,
			'quote_id'        => $validated['quote_id'],
			'sender_id'       => auth()->user()->id,
			'is_like'         => true,
		]);

		NotificationSent::dispatch(['user' => User::where('id', $author->id)->first(), 'quote' => Quote::where('id', $validated['quote_id'])->first(), 'sender'=>User::where('id', auth()->user()->id)->first(), 'notification' => $notification]);

		return response()->json('liked', 200);
	}
}
