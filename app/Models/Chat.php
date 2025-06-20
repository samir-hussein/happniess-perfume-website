<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
	protected $fillable = [
		"id",
		"client_id",
		"client_ip",
	];

	public function client()
	{
		return $this->belongsTo(Client::class);
	}

	public function messages()
	{
		return $this->hasMany(Message::class);
	}
}
