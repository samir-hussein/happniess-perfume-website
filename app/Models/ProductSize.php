<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
	protected $fillable = ['size', 'price', 'quantity', 'product_id'];
}
