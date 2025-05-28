<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionalCode extends Model
{
    protected $fillable = [
        'code',
        'discount_amount',
        'discount_type',
        'start_date',
        'end_date',
        'usage_limit',
        'actual_usage',
        'minimum_order_amount',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function getStartDateAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function getEndDateAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function isActive()
    {
        return (($this->start_date <= now() && $this->end_date >= now()) || ($this->start_date == null && $this->end_date == null) || ($this->start_date <= now() && $this->end_date == null)) && ($this->usage_limit == null || $this->usage_limit > $this->actual_usage);
    }
}
