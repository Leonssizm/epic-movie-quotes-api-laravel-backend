<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Translatable\HasTranslations;

class Quote extends Model
{
	use HasFactory, HasTranslations;

	public $translatable = ['body'];

	protected $guarded = ['id'];

	public function scopeFilter($query, array $filters)
	{
		$query->when($filters['search'] ?? false, function ($query, $search) {
			$search = ucwords(strtolower($search));

			if (str_starts_with($search, '@')) {
				$movieName = substr($search, 1);
				$query->whereHas('movie', function ($q) use ($movieName) {
					$q->where('title->en', 'like', '%' . ucwords($movieName) . '%')
						->orWhere('title->ka', 'like', '%' . $movieName . '%');
				});
			}
			if (str_starts_with($search, '#')) {
				$quoteName = substr($search, 1);
				$query->where('body->en', 'like', '%' . (ucwords($quoteName)) . '%')
					->orWhere('body->ka', 'like', '%' . ($quoteName) . '%');
			}
		});
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function movie(): BelongsTo
	{
		return $this->belongsTo(Movie::class);
	}

	public function likes()
	{
		return $this->belongsToMany(User::class, 'quote_user')->withTimestamps();
	}

	public function likedByUsers(): BelongsToMany
	{
		return $this->belongsToMany(User::class, 'quote_user');
	}

	public function comments(): HasMany
	{
		return $this->hasMany(Comment::class);
	}

	public function notifications(): MorphMany
	{
		return $this->morphMany(Notification::class, 'notifiable');
	}
}
