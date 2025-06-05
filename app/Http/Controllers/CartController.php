<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\ICartService;
use App\Http\Requests\SyncCartRequest;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\RemoveFromCartRequest;
use App\Http\Requests\GetCartProductsRequest;
use App\Http\Requests\UpdateCartQuantityRequest;

class CartController extends Controller
{
    public function __construct(private ICartService $cartService) {}

    public function getCartProducts(string $locale, GetCartProductsRequest $request)
    {
        return response()->json($this->cartService->getCartProducts($request->validated()));
    }

    public function addToCart(AddToCartRequest $request)
    {
        return response()->json($this->cartService->addToCart($request->validated(), $request->user()->id));
    }

    public function getCartCount(Request $request)
    {
        return response()->json($this->cartService->getCartCount($request->user()->id));
    }

    public function removeFromCart(RemoveFromCartRequest $request)
    {
        return response()->json($this->cartService->removeFromCart($request->validated(), $request->user()->id));
    }

    public function updateCartQuantity(UpdateCartQuantityRequest $request)
    {
        return response()->json($this->cartService->updateCartQuantity($request->validated(), $request->user()->id));
    }

    public function syncCart(SyncCartRequest $request)
    {
        return response()->json($this->cartService->syncCart($request->validated(), $request->user()->id));
    }
}
