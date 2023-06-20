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
		$notification = Notification::firstWhere('receiver_id', auth()->user()->id);

		if ($notification !== null) {
			$quote = Quote::find($notification->quote_id);

			$receiver = User::find($quote->user_id);

			$sender = User::find($notification->sender_id);

			$response = ['quote' => $quote, 'user'=>$receiver, 'sender' => $sender, 'notification'=>$notification];

			return response()->json([$response], 200);
		} else {
			return response()->json('no notifications yet', 204);
		}
	}
}
