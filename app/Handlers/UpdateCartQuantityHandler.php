<?php

namespace App\Handlers;

use App\Interfaces\ICartRepo;

class UpdateCartQuantityHandler
{
	public function __construct(private ICartRepo $cartRepo) {}

	public function __invoke(array $data, int $clientId)
	{
		$data["client_id"] = $clientId;

		$cartItem = $this->cartRepo->getCartItemByProductIdAndSizeAndClientId($data["product_id"], $data["size"], $data["client_id"]);

		if ($data["action"] == "plus") {
			$cartItem->quantity += 1;
			$cartItem->save();
			return $cartItem;
		}

		if ($data["action"] == "minus") {
			$cartItem->quantity -= 1;
			if ($cartItem->quantity == 0) {
				$cartItem->delete();
				return null;
			}
			$cartItem->save();
			return $cartItem;
		}
	}
}
