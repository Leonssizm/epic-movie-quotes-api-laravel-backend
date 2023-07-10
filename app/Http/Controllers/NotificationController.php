<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
	public function index(): JsonResponse
	{
		$notifications = Notification::where('receiver_id', auth()->id())->get();

		if ($notifications->count() > 0) {
			$response = [];

			foreach ($notifications as $notification) {
				$response[] = [
					'quote'        => $notification->notifiable,
					'user'         => $notification->notifiable->user,
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

	public function readAllNotifications(): JsonResponse
	{
		$notifications = Notification::where('receiver_id', auth()->id())->get();

		$response = [];

		foreach ($notifications as $notification) {
			$notification->is_new = false;
			$notification->save();
			$response[] = [
				'quote'        => $notification->notifiable,
				'user'         => $notification->notifiable->user,
				'sender'       => $notification->sender,
				'notification' => $notification,
			];
		}

		return response()->json($response, 200);
	}
}
