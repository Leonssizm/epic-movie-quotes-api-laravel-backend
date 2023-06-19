<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
	public function index(): JsonResponse
	{
		$notification = Notification::where('receiver_id', auth()->user()->id)->first();

		if ($notification !== null) {
			$quote = Quote::where('id', $notification->quote_id)->first();

			$receiver = User::where('id', $quote->user_id)->first();

			$sender = User::where('id', $notification->sender_id)->first();

			$response = ['quote' => $quote, 'user'=>$receiver, 'sender' => $sender, 'notification'=>$notification];

			return response()->json([$response], 200);
		} else {
			return response()->json('no notifications yet', 204);
		}
	}
}
