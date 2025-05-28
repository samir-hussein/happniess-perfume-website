<?php

namespace App\Handlers;

use App\Interfaces\ICartRepo;

class SyncCartHandler
{
	public function __construct(private ICartRepo $cartRepo) {}

	public function __invoke(array $data, int $userId)
	{
		foreach ($data['products'] as $product) {
			$cartItem = $this->cartRepo->getCartItemByProductIdAndSizeAndClientId($product['product_id'], $product['size'], $userId);
			if ($cartItem) {
				$cartItem->quantity += $product['quantity'];
				$cartItem->save();
			} else {
				$this->cartRepo->create([
					"product_id" => $product['product_id'],
					"size" => $product['size'],
					"quantity" => $product['quantity'],
					"client_id" => $userId,
				]);
			}
		}
	}
}
