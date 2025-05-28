<?php

namespace App\Handlers;

use App\Interfaces\IOrderItemsRepo;
use Illuminate\Support\Facades\Auth;

class ReorderHandler
{
	public function __construct(private IOrderItemsRepo $orderItemsRepo, private SyncCartHandler $syncCartHandler) {}

	public function __invoke(int $orderId)
	{
		$orderItems = $this->orderItemsRepo->getOrderItems($orderId);

		$products = [];

		foreach ($orderItems as $orderItem) {
			$products[] = [
				"product_id" => $orderItem->product_id,
				"size" => $orderItem->size,
				"quantity" => $orderItem->quantity,
			];
		}

		$this->syncCartHandler->__invoke([
			"products" => $products,
		], Auth::user()->id);

		return $products;
	}
}
