<?php

namespace App\Interfaces;

interface ICartRepo extends IRepository
{
	public function getCartItemByProductIdAndSizeAndClientId(int $productId, string $size, int $clientId);

	public function getCartCount(int $clientId);

	public function getCartProductsByClientId(int $clientId);

	public function removeFromCart(int $productId, string $size, int $clientId);

	public function clearCart(int $clientId);
}
