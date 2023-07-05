<?php

namespace App\Http\Controllers;

use App\Events\NotificationSent;
use App\Http\Requests\Like\LikeRequest;
use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
	public function like(LikeRequest $request)
	{
		$validated = $request->validated();
		$quote = Quote::find($validated['quote_id']);
		$author = $quote->user;

		$alreadyLiked = DB::table('quote_user')->where('quote_id', $quote->id)->where('user_id', $validated['user_id'])->first();

		if ($alreadyLiked) {
			DB::table('quote_user')->where('quote_id', $validated['quote_id'])->where('user_id', $validated['user_id'])->delete();

			Notification::where('receiver_id', $author->id)->where('sender_id', $validated['user_id'])->where('is_like', true)->delete();

			return response()->json('unlike', 200);
		}

		DB::table('quote_user')->insert([
			'quote_id' => $validated['quote_id'],
			'user_id'  => $validated['user_id'],
		]);

		if ($author->id !== auth()->id()) {
			$notification = Notification::create([
				'receiver_id'     => $author->id,
				'notifiable_id'   => $validated['quote_id'],
				'notifiable_type' => Quote::class,
				'sender_id'       => auth()->id(),
				'is_like'         => true,
			]);

			$sender = auth()->user();

			NotificationSent::dispatch(['user' => $author, 'quote' => $quote, 'sender' => $sender, 'notification' => $notification]);
		}

		return response()->json('liked', 200);
	}
}
