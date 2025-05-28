<?php

namespace App\Handlers;

use App\Interfaces\IShippingMethodRepo;

class GetAllShippingMethodsHandler
{
	public function __construct(private IShippingMethodRepo $shipmentMethodRepo) {}

	public function __invoke()
	{
		return $this->shipmentMethodRepo->getAll();
	}
}
