<?php

namespace App\Http\Controllers;

use App\Events\NotificationSent;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
	public function index(): JsonResponse
	{
		return response()->json(Comment::with('user')->get(), 200);
	}

	public function addComment(StoreCommentRequest $request, Comment $comment): JsonResponse
	{
		$validateCommentRequest = $request->validated();

		$newComment = $comment->create([
			'user_id' => $validateCommentRequest['user_id'],
			'quote_id'=> $validateCommentRequest['quote_id'],
			'body'    => $validateCommentRequest['body'],
		])->load('user');

		$author = User::find(Quote::where('id', $newComment->quote_id)->first()->user_id);

		$notification = Notification::create([
			'receiver_id'        => $author->id,
			'quote_id'           => $newComment['quote_id'],
			'sender_id'          => auth()->user()->id,
			'is_comment'         => true,
		]);

		$quote = Quote::find($notification['quote_id']);
		$sender = User::find(auth()->user()->id);

		NotificationSent::dispatch(['user' => $author, 'quote' => $quote, 'sender'=>$sender, 'notification' => $notification]);

		return response()->json($newComment, 200);
	}
}
