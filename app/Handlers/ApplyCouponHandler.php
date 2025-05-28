<?php

namespace App\Handlers;

use App\Interfaces\IPromotionalCodeRepo;
use App\Handlers\GetCartProductsHandler;

class ApplyCouponHandler
{
    public function __construct(private IPromotionalCodeRepo $promotionalCodeRepo, private GetCartProductsHandler $getCartProductsHandler) {}

    public function __invoke(string $code)
    {
        $promotionalCode = $this->promotionalCodeRepo->getByCode($code);

        if (!$promotionalCode) {
            throw new \Exception(__('Invalid coupon code'));
        }

        if (!$promotionalCode->isActive()) {
            throw new \Exception(__('Coupon code is not active'));
        }

        // get products in cart
        $cartProducts = $this->getCartProductsHandler->__invoke(null, request()->user()->id);

        if ($promotionalCode->minimum_order_amount && $cartProducts['totalAsNumber'] < $promotionalCode->minimum_order_amount) {
            throw new \Exception(__('coupon code is not valid for this order'));
        }

        // apply coupon
        if ($promotionalCode->discount_type === 'percentage') {
            $discount = $cartProducts['totalAsNumber'] * $promotionalCode->discount_amount / 100;
        } else {
            $discount = $promotionalCode->discount_amount;
        }

        return [
            "discount" => ceil($discount),
            "cartProducts" => $cartProducts,
        ];
    }
}
