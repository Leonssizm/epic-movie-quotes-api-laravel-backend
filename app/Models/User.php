<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
	use HasApiTokens;

	use HasFactory;

	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $guarded = ['id'];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	protected function setPasswordAttribute($value)
	{
		$this->attributes['password'] = Hash::make($value);
	}

	public function movies(): HasMany
	{
		return $this->hasMany(Movie::class);
	}

	public function quotes(): HasMany
	{
		return $this->hasMany(Quote::class);
	}

	public function comments(): HasMany
	{
		return $this->hasMany(Comment::class);
	}

	public function likes(): HasMany
	{
		return $this->hasMany(Like::class);
	}

	public function notifications(): HasMany
	{
		return $this->hasMany(Notification::class);
	}
}
