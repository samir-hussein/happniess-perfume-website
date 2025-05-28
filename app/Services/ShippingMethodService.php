<?php

namespace App\Services;

use App\Interfaces\IShippingMethodRepo;
use App\Interfaces\IShippingMethodService;

class ShippingMethodService implements IShippingMethodService
{
	public function __construct(
		private IShippingMethodRepo $shippingMethodRepo
	) {}

	public function getAll()
	{
		return $this->shippingMethodRepo->getAll();
	}
}
