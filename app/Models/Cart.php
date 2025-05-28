<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
	protected $fillable = [
		'client_id',
		'product_id',
		'size',
		'quantity',
	];

	public function client()
	{
		return $this->belongsTo(Client::class);
	}
}
