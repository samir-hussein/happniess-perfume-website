<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FCMToken extends Model
{
	protected $fillable = [
		"token",
		"user_id",
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
