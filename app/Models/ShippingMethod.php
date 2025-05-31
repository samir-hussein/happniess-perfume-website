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

	public static function calculateShipping($governorate, $subtotal)
	{
		if ($governorate == null) {
			return 0;
		}

		$remoteAreas = [
			'matruh',
			'new_valley',
			'north_sinai',
			'south_sinai',
			'red_sea'
		];

		if (in_array($governorate, $remoteAreas)) {
			$shippingMethod = ShippingMethod::where('name_en', 'Remote Areas')->first();

			if ($shippingMethod->minimum_order_amount && $subtotal >= $shippingMethod->minimum_order_amount) {
				return 0;
			}
			return ceil($shippingMethod->cost);
		}

		if ($governorate === 'cairo' || $governorate === 'giza') {
			$shippingMethod = ShippingMethod::where('name_en', 'Cairo & Giza')->first();

			if ($shippingMethod->minimum_order_amount && $subtotal >= $shippingMethod->minimum_order_amount) {
				return 0;
			}
			return ceil($shippingMethod->cost);
		}

		$shippingMethod = ShippingMethod::where('name_en', 'Other Governorates')->first();

		if ($shippingMethod->minimum_order_amount && $subtotal >= $shippingMethod->minimum_order_amount) {
			return 0;
		}

		return ceil($shippingMethod->cost);
	}
}
