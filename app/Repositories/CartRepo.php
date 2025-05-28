<?php

namespace App\Repositories;

use App\Interfaces\ICartRepo;
use App\Models\Cart;
use App\Repositories\BaseRepository;

class CartRepo extends BaseRepository implements ICartRepo
{
	public function __construct(Cart $cart)
	{
		parent::__construct($cart);
	}

	public function getCartItemByProductIdAndSizeAndClientId(int $productId, string $size, int $clientId)
	{
		return $this->model->where('product_id', $productId)->where('size', $size)->where('client_id', $clientId)->first();
	}

	public function getCartCount(int $clientId)
	{
		return $this->model->where('client_id', $clientId)->sum('quantity');
	}

	public function getCartProductsByClientId(int $clientId)
	{
		return $this->model->where('client_id', $clientId)->get([
			"product_id",
			"size",
			"quantity",
		]);
	}

	public function removeFromCart(int $productId, string $size, int $clientId)
	{
		return $this->model->where('product_id', $productId)->where('size', $size)->where('client_id', $clientId)->delete();
	}

	public function clearCart(int $clientId)
	{
		return $this->model->where('client_id', $clientId)->delete();
	}
}
