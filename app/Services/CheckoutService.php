<?php

namespace App\Services;

use App\Handlers\ApplyCouponHandler;
use App\Handlers\PlaceOrderHandler;
use App\Interfaces\ICheckoutService;

class CheckoutService implements ICheckoutService
{
	public function __construct(private ApplyCouponHandler $applyCouponHandler, private PlaceOrderHandler $placeOrderHandler) {}

	public function applyCoupon(array $data)
	{
		return $this->applyCouponHandler->__invoke($data["code"]);
	}

	public function placeOrder(array $data)
	{
		return $this->placeOrderHandler->__invoke($data);
	}
}
