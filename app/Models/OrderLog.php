<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    protected $fillable = [
        'order_id',
        'action_ar',
        'action_en',
        'description_ar',
        'description_en',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
