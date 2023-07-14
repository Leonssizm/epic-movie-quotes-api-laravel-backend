<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
	public function index()
	{
		$notifications = Notification::where('receiver_id', auth()->id())->get()->load('notifiable', 'sender', 'notifiable.user');

		if ($notifications->count() > 0) {
			return response()->json($notifications, 200);
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
		$notifications = Notification::where('receiver_id', auth()->id())->get()->load('notifiable', 'sender', 'notifiable.user');

		foreach ($notifications as $notification) {
			$notification->is_new = false;
			$notification->save();
		}

		return response()->json($notifications, 200);
	}
}
