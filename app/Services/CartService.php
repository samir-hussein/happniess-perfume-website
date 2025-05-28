<?php

namespace App\Services;

use App\Interfaces\ICartService;
use App\Handlers\GetCartProductsHandler;
use App\Handlers\AddToCartHandler;
use App\Handlers\UpdateCartQuantityHandler;
use App\Repositories\CartRepo;
use App\Handlers\SyncCartHandler;

class CartService implements ICartService
{
    public function __construct(private GetCartProductsHandler $getCartProductsHandler, private AddToCartHandler $addToCartHandler, private CartRepo $cartRepo, private UpdateCartQuantityHandler $updateCartQuantityHandler, private SyncCartHandler $syncCartHandler) {}

    public function getCartProducts(array $data)
    {
        return $this->getCartProductsHandler->__invoke($data["products"] ?? null, $data["user_id"] ?? null);
    }

    public function addToCart(array $data, int $clientId)
    {
        return $this->addToCartHandler->__invoke($data, $clientId);
    }

    public function removeFromCart(array $data, int $clientId)
    {
        $data["client_id"] = $clientId;
        return $this->cartRepo->removeFromCart($data["product_id"], $data["size"], $data["client_id"]);
    }

    public function updateCartQuantity(array $data, int $clientId)
    {
        return $this->updateCartQuantityHandler->__invoke($data, $clientId);
    }

    public function syncCart(array $data, int $clientId)
    {
        return $this->syncCartHandler->__invoke($data, $clientId);
    }

    public function getCartCount(int $clientId)
    {
        return $this->cartRepo->getCartCount($clientId);
    }
}
