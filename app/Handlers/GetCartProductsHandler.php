<?php

namespace App\Handlers;

use App\Interfaces\ICartRepo;
use App\Models\ShippingMethod;
use App\Interfaces\IProductRepo;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Auth;

class GetCartProductsHandler
{
    public function __construct(private IProductRepo $productRepo, private ICartRepo $cartRepo) {}

    public function __invoke(array|null $data, int|null $userId)
    {
        $removedSizes = [];

        if ($userId) {
            if ($userId != Auth::user()->id) {
                throw new GeneralException("You are not authorized to access this cart");
            }

            $data = $this->cartRepo->getCartProductsByClientId($userId)->toArray();
        }

        if ($data) {
            $ids = array_column($data, 'product_id');
            $products = $this->productRepo->getProductsForCart($ids);

            $cartProducts = [];
            $cartProductsTotal = 0;

            foreach ($data as $item) {
                $product = $products->where('id', $item['product_id'])->first();
                $size = $product->sizes->where('size', $item['size'])->first();

                if (!$size) {
                    $removedSizes[] = [
                        "product_id" => $item['product_id'],
                        "size" => $item['size'],
                    ];
                    continue;
                }

                if ($product->discount_amount > 0) {
                    if ($product->discount_type === "percentage") {
                        $size->priceAfterDiscount = ($size->price - ($size->price * $product->discount_amount / 100));
                        $cartProductsTotal += $size->priceAfterDiscount * $item['quantity'];
                    } else {
                        $size->priceAfterDiscount = ($size->price - $product->discount_amount);
                        $cartProductsTotal += $size->priceAfterDiscount * $item['quantity'];
                    }
                } else {
                    $size->priceAfterDiscount = $size->price;
                    $cartProductsTotal += $size->priceAfterDiscount * $item['quantity'];
                }
                $cartProducts[] = [
                    "product" => $product,
                    "size" => $size,
                    "quantity" => $item['quantity'],
                ];
            }

            if (count($removedSizes) > 0 && $userId) {
                foreach ($removedSizes as $size) {
                    $this->cartRepo->removeFromCart($size['product_id'], $size['size'], $userId);
                }

                $removedSizes = [];
            }

            $shippingCost = 0;
            if ($userId) {
                $shippingCost = ShippingMethod::calculateShipping(Auth::user()->city, $cartProductsTotal);
            }


            return [
                "products" => $cartProducts,
                "total" => number_format($cartProductsTotal, 2, '.', ','),
                "totalAsNumber" => $cartProductsTotal,
                "removedSizes" => $removedSizes,
                "shippingCost" => $shippingCost,
            ];
        }

        return [
            "products" => [],
            "total" => 0,
            "totalAsNumber" => 0,
            "removedSizes" => [],
            "shippingCost" => 0,
        ];
    }
}
