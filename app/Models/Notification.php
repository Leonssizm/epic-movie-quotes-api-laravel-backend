<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
	use HasFactory;

	protected $fillable = ['receiver_id', 'notifiable_id', 'notifiable_type', 'is_like', 'is_comment', 'sender_id'];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'receiver_id');
	}

	public function notifiable(): MorphTo
	{
		return $this->morphTo();
	}

	public function sender(): BelongsTo
	{
		return $this->belongsTo(User::class, 'sender_id');
	}
}
