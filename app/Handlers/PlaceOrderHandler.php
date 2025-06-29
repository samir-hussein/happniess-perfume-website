<?php

namespace App\Handlers;

use App\Models\FCMToken;
use App\Facades\Firebase;
use App\Interfaces\ICartRepo;
use App\Interfaces\IOrderRepo;
use App\Models\ShippingMethod;
use App\Facades\PaymentGateway;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\GeneralException;
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
        $products = [];

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

            $products[] = [
                "name" => $product['product']['name'],
                "price" => floatval($product['size']['priceAfterDiscount']),
                "quantity" => $product['quantity']
            ];
        }

        DB::beginTransaction();
        try {
            // Get and lock all product sizes in one query to prevent deadlocks
            $lockedProductSizes = [];
            foreach ($orderItems as $sizeToLock) {
                $productSize = DB::table('product_sizes')
                    ->where('product_id', $sizeToLock['product_id'])
                    ->where('size', $sizeToLock['size'])
                    ->lockForUpdate() // This will lock the rows until the transaction is committed or rolled back
                    ->first();

                if (!$productSize) {
                    throw new GeneralException(__('Product with size :size not found', ['size' => $sizeToLock['size']]));
                }

                $lockedProductSizes[$sizeToLock['product_id'] . '-' . $sizeToLock['size']] = $productSize;
            }

            // Check if all products are in stock after acquiring locks
            foreach ($cartProducts['products'] as $cartProduct) {
                $key = $cartProduct['product']['id'] . '-' . $cartProduct['size']['size'];
                $productSize = $lockedProductSizes[$key];

                if ($productSize->quantity < $cartProduct['quantity']) {
                    $productName = $cartProduct['product']['name'];
                    $sizeName = $cartProduct['size']['size'];

                    throw new GeneralException(
                        __('Product ":product" with size ":size" ml has only :available items in stock, but you requested :requested items.', [
                            'product' => $productName,
                            'size' => $sizeName,
                            'available' => $productSize->quantity,
                            'requested' => $cartProduct['quantity']
                        ])
                    );
                }
            }

            // create order
            $order = $this->orderRepo->createOrder($order, $orderItems);

            // Update product quantities
            foreach ($cartProducts['products'] as $cartProduct) {
                DB::table('product_sizes')
                    ->where('product_id', $cartProduct['product']['id'])
                    ->where('size', $cartProduct['size']['size'])
                    ->decrement('quantity', $cartProduct['quantity']);
            }

            // update client personal data
            $this->updateClientPersonalData($data);

            // increment coupon usage
            if (data_get($data, 'coupon')) {
                $this->promotionalCodeRepo->incrementUsage(data_get($data, 'coupon'));
            }

            // delete cart products
            $this->cartRepo->clearCart(request()->user()->id);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new GeneralException($th->getMessage());
        }

        $preparedOrderData = [
            "order_id" => $order->id,
            "order_number" => $order->order_number,
            "created_at" => $order->created_at->format('Y-m-d'),
            "total_price" => number_format($order->total_price, 2, '.', ','),
            "total_price_number" => $subtotal + $shipping,
            "payment_method" => $this->paymentMethodsNamesMatch($order->payment_method),
            "discount_value" => $discount,
        ];

        // send notification
        $payload = [
            'title' => "New Order",
            'message' => "New order has been placed by " . request()->user()->name . " at " . now()->format('Y-m-d H:i:s') . " with order number " . $order->order_number . " and total price " . $order->total_price . " EGP",
            'url' => env("PANEL_URL") . "/orders/" . $order->id
        ];

        $tokens = FCMToken::get()->pluck('token')->toArray();

        try {
            Firebase::sendNotification($tokens, $payload);
        } catch (\Throwable $th) {
            Log::info($th->getMessage() . " : " . $th->getTraceAsString());
        }

        // handle payment
        if ($data['payment'] != 'cash_on_delivery') {
            $firstName = explode(" ", request()->user()->name)[0];
            $lastName = explode(" ", request()->user()->name)[1] ?? "-";
            $products[] = [
                "name" => app()->getLocale() == "ar" ? "الشحن" : "Shipping",
                "price" => $shipping,
                "quantity" => 1
            ];

            try {
                $res = PaymentGateway::pay(
                    $preparedOrderData,
                    [
                        "first_name" => $firstName,
                        "last_name" => $lastName,
                        "email" => request()->user()->email,
                        "phone" => $data['phone'],
                        "address" => $data['address']
                    ],
                    $products
                );

                if ($data['payment'] == 'wallet') {
                    $order->update([
                        "invoice_id" => $res->invoice_id,
                        "invoice_key" => $res->invoice_key,
                        "payment_link" => $res->payment_data->meezaQrCode,
                        "reference_number" => $res->payment_data->meezaReference,
                    ]);
                } else {
                    $order->update([
                        "invoice_id" => $res->invoice_id,
                        "invoice_key" => $res->invoice_key,
                        "payment_link" => $res->payment_data->redirectTo,
                    ]);
                }

                $preparedOrderData['payment_link'] = $order->payment_link;
                $preparedOrderData['reference_number'] = $order->reference_number;
            } catch (\Throwable $th) {
                Log::info($th->getMessage() . " : " . $th->getTraceAsString());
            }
        }

        // return order data
        return [
            "order" => $preparedOrderData,
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
            'card' => 'Card',
            'wallet' => 'Wallet',
            default => 'Cash on delivery',
        };
    }
}
