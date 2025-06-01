<?php

namespace App\Handlers;

use App\Interfaces\IOrderRepo;
use App\Interfaces\IOrderItemsRepo;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Auth;

class ReorderHandler
{
	public function __construct(private IOrderItemsRepo $orderItemsRepo, private SyncCartHandler $syncCartHandler, private IOrderRepo $orderRepo) {}

	public function __invoke(int $orderId)
	{
		$order = $this->orderRepo->getById($orderId);
		if ($order->client_id != Auth::user()->id) {
			throw new GeneralException("You are not authorized to reorder this order");
		}

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
