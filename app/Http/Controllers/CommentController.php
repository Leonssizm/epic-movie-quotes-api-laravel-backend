<?php

namespace App\Http\Controllers;

use App\Events\NotificationSent;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
	public function index(Request $request)
	{
		$comments = Comment::where('quote_id', $request->input('quote_id'))->get()->load('user');

		return response()->json($comments, 200);
	}

	public function store(StoreCommentRequest $request, Comment $comment): JsonResponse
	{
		$newComment = $comment->create($request->validated())->load('user');

		$author = User::find(Quote::where('id', $newComment->quote_id)->first()->user_id);

		if ($author->id !== auth()->id()) {
			$notification = Notification::create([
				'receiver_id'     => $author->id,
				'notifiable_id'   => $newComment['quote_id'],
				'notifiable_type' => Quote::class,
				'sender_id'       => auth()->id(),
				'is_comment'      => true,
				'is_new'          => true,
			]);

			$quote = Quote::find($newComment['quote_id']);
			$sender = auth()->user();

			NotificationSent::dispatch(['user' => $author, 'quote' => $quote, 'sender' => $sender, 'notification' => $notification]);
		}

		return response()->json($newComment, 200);
	}
}
