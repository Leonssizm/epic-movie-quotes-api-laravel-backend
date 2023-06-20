<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
	use HasFactory;

	protected $fillable = ['receiver_id', 'quote_id', 'sender_id', 'is_like', 'is_comment'];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function sender(): BelongsTo
	{
		return $this->belongsTo(User::class, 'sender_id', 'id');
	}

	public function quote(): BelongsTo
	{
		return $this->belongsTo(Quote::class, 'quote_id', 'id');
	}
}
