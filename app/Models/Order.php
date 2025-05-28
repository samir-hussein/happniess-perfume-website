<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $fillable = [
		'order_number',
		'client_id',
		'promotional_code',
		'sub_total_price',
		'shipping_cost',
		'discount_amount',
		'total_price',
		'payment_method',
		'payment_status',
		'transaction_id',
		'order_status',
		'city',
		'address'
	];

	protected static function booted()
	{
		static::creating(function ($order) {
			$now = Carbon::now();
			$year = $now->format('y'); // e.g. '25'
			$month = $now->format('m'); // e.g. '05'
			$prefix = "HP-{$year}{$month}";

			// Get the last order code with the same year-month prefix
			$lastOrder = self::where('order_number', 'like', "{$prefix}%")
				->orderByDesc('order_number')
				->first();

			if ($lastOrder && preg_match('/HP-\d{4}(\d{4})/', $lastOrder->order_number, $matches)) {
				$lastSequence = (int) $matches[1];
			} else {
				$lastSequence = 0;
			}

			$newSequence = str_pad($lastSequence + 1, 4, '0', STR_PAD_LEFT);
			$order->order_number = "{$prefix}{$newSequence}";
		});
	}

	public function client()
	{
		return $this->belongsTo(Client::class);
	}

	public function orderItems()
	{
		return $this->hasMany(OrderItem::class);
	}

	public function orderLogs()
	{
		return $this->hasMany(OrderLog::class);
	}
}
