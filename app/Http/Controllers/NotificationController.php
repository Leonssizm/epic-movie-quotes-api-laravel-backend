<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
	public function index()
	{
		$notifications = Notification::where('receiver_id', auth()->id())->get();

		$notifications->load(['quote', 'sender']);
		if ($notifications->count() > 0) {
			$response = [];

			foreach ($notifications as $notification) {
				$receiver = User::find($notification->quote->user_id);

				$response[] = [
					'quote'        => $notification->quote,
					'user'         => $receiver,
					'sender'       => $notification->sender,
					'notification' => $notification,
				];
			}

			return response()->json($response, 200);
		} else {
			return response()->json('no notifications yet', 204);
		}
	}

	public function makeNotificationRead(Notification $notification): JsonResponse
	{
		$notification->is_new = false;
		$notification->save();
		return response()->json('success', 200);
	}
}
