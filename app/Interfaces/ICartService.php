<?php

namespace App\Interfaces;

interface ICartService
{
	public function getCartProducts(array $data);
	public function addToCart(array $data, int $clientId);
	public function removeFromCart(array $data, int $clientId);
	public function updateCartQuantity(array $data, int $clientId);
	public function getCartCount(int $clientId);
	public function syncCart(array $data, int $clientId);
}
