<?php

namespace App\Handlers;

use App\Repositories\CartRepo;

class AddToCartHandler
{
    public function __construct(private CartRepo $cartRepo) {}

    public function __invoke(array $data, int $clientId)
    {
        $data["client_id"] = $clientId;

        $cart = $this->cartRepo->getCartItemByProductIdAndSizeAndClientId($data["product_id"], $data["size"], $data["client_id"]);

        if ($cart) {
            $cart->quantity += $data["quantity"];
            $cart->save();
            return $cart;
        }

        return $this->cartRepo->create($data);
    }
}
