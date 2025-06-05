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
