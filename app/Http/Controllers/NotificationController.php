<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
	public function index(): JsonResponse
	{
		$notifications = Notification::where('receiver_id', auth()->user()->id)->get();

		if ($notifications->count() > 0) {
			$response = [];

			foreach ($notifications as $notification) {
				$quote = Quote::find($notification->quote_id);
				$receiver = User::find($quote->user_id);
				$sender = User::find($notification->sender_id);

				$response[] = [
					'quote'        => $quote,
					'user'         => $receiver,
					'sender'       => $sender,
					'notification' => $notification,
				];
			}

			return response()->json($response, 200);
		} else {
			return response()->json('no notifications yet', 204);
		}
	}

	public function makeNotificationRead(Request $request, Notification $notification): JsonResponse
	{
		$notification->is_new = false;
		$notification->save();
		return response()->json('success', 200);
	}
}
