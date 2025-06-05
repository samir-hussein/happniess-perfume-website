<?php

namespace App\Handlers;

use App\Interfaces\IOrderRepo;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\GeneralException;

class GetInvoiceHandler
{
	public function __construct(private IOrderRepo $orderRepo) {}

	public function __invoke(int $orderId)
	{
		$order = $this->orderRepo->getById($orderId);

		if ($order->client_id != Auth::user()->id) {
			throw new GeneralException("You are not authorized to view this order");
		}

		if (!$order->payment_link) {
			throw new GeneralException("Invoice not found");
		}

		return [
			"payment_link" => $order->payment_link,
			"reference_number" => $order->reference_number,
			"order_id" => $order->id,
			"order_number" => $order->order_number,
			"created_at" => $order->created_at->format('Y-m-d'),
			"total_price" => number_format($order->total_price, 2, '.', ','),
			"total_price_number" => $order->sub_total_price + $order->shipping_cost,
			"payment_method" => $this->paymentMethodsNamesMatch($order->payment_method),
			"discount_value" => $order->discount_amount,
		];
	}

	public function paymentMethodsNamesMatch($paymentMethod)
	{
		return match ($paymentMethod) {
			'cash_on_delivery' => 'Cash on delivery',
			'card' => 'Card',
			'wallet' => 'Wallet',
			default => 'Cash on delivery',
		};
	}
}
