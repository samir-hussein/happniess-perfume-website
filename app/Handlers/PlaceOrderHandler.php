<?php

namespace App\Handlers;

use App\Interfaces\ICartRepo;
use App\Interfaces\IOrderRepo;
use App\Models\ShippingMethod;
use Illuminate\Support\Facades\DB;
use App\Handlers\ApplyCouponHandler;
use Illuminate\Support\Facades\Auth;
use App\Handlers\GetCartProductsHandler;
use App\Interfaces\IPromotionalCodeRepo;

class PlaceOrderHandler
{
    public function __construct(private GetCartProductsHandler $getCartProductsHandler, private ApplyCouponHandler $applyCouponHandler, private IOrderRepo $orderRepo, private IPromotionalCodeRepo $promotionalCodeRepo, private ICartRepo $cartRepo) {}

    public function __invoke(array $data)
    {
        $discount = 0;
        $total = 0;
        $shipping = 0;
        $subtotal = 0;
        $cartProducts = null;

        // check if coupon is valid
        if ($data['coupon']) {
            $result = $this->applyCouponHandler->__invoke($data['coupon']);
            $discount = $result['discount'];
            $cartProducts = $result['cartProducts'];
        }

        if (!$cartProducts) {
            $cartProducts = $this->getCartProductsHandler->__invoke(null, request()->user()->id);
        }

        // check if cart is empty
        if (count($cartProducts['products']) === 0) {
            throw new \Exception(__('Cart is empty'));
        }

        // calculate subtotal and shipping cost
        $subtotal = $cartProducts['totalAsNumber'];
        $shipping = ShippingMethod::calculateShipping($data['city'], $subtotal);
        $total = $subtotal + $shipping - $discount;

        // prepare order data
        $order = [
            "client_id" => request()->user()->id,
            "promotional_code" => $data['coupon'] ?? null,
            "discount_amount" => $discount,
            "sub_total_price" => $subtotal,
            "shipping_cost" => $shipping,
            "total_price" => $total,
            "payment_method" => $data['payment'],
            "payment_status" => "unpaid",
            "city" => $data['city'],
            "address" => $data['address'],
        ];

        $orderItems = [];

        // prepare order items
        foreach ($cartProducts['products'] as $product) {
            $orderItems[] = [
                'product_id' => $product['product']['id'],
                'quantity' => $product['quantity'],
                'size' => $product['size']['size'],
                'price' => floatval($product['size']['priceAfterDiscount']),
                'total_price' => round(floatval($product['size']['priceAfterDiscount']) * $product['quantity'], 2),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        try {
            DB::beginTransaction();
            // create order
            $order = $this->orderRepo->createOrder($order, $orderItems);

            // update client personal data
            $this->updateClientPersonalData($data);

            // increment coupon usage
            if ($data['coupon']) {
                $this->promotionalCodeRepo->incrementUsage($data['coupon']);
            }

            // delete cart products
            $this->cartRepo->clearCart(request()->user()->id);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage());
        }

        // return order data
        return [
            "order" => [
                "order_number" => $order->order_number,
                "created_at" => $order->created_at->format('Y-m-d'),
                "total_price" => number_format($order->total_price, 2, '.', ','),
                "payment_method" => $this->paymentMethodsNamesMatch($order->payment_method),
            ],
        ];
    }

    private function updateClientPersonalData(array $data)
    {
        tap(Auth::user())->update([
            "name" => $data['name'],
            "address" => $data['address'],
            "city" => $data['city'],
            "phone" => $data['phone'],
        ]);
    }

    private function paymentMethodsNamesMatch($paymentMethod)
    {
        return match ($paymentMethod) {
            'cash_on_delivery' => 'Cash on delivery',
            default => 'Cash on delivery',
        };
    }
}
