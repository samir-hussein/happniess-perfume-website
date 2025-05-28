<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'cost',
        'delivery_time_ar',
        'delivery_time_en',
        'minimum_order_amount',
        'is_active',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
