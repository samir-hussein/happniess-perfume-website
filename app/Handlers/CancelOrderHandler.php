<?php

namespace App\Handlers;

use App\Models\FCMToken;
use App\Facades\Firebase;
use App\Interfaces\IOrderRepo;
use Illuminate\Support\Facades\Log;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CancelOrderHandler
{
	public function __construct(private IOrderRepo $orderRepo) {}

	public function __invoke(int $orderId)
	{
		$order = $this->orderRepo->getById($orderId);

		if ($order->client_id != Auth::user()->id) {
			throw new GeneralException("You are not authorized to cancel this order");
		}

		if ($order->order_status != 'pending') {
			throw new GeneralException("You can't cancel this order");
		}

		$this->orderRepo->cancelOrder($orderId);

		// update product quantities
		foreach ($order->orderItems as $orderItem) {
			DB::table('product_sizes')
				->where('product_id', $orderItem->product_id)
				->where('size', $orderItem->size)
				->increment('quantity', $orderItem->quantity);
		}

		// send notification
		$payload = [
			'title' => "Order Cancelled",
			'message' => "Order has been cancelled by " . request()->user()->name . " at " . now()->format('Y-m-d H:i:s') . " with order number " . $order->order_number,
			'url' => env("PANEL_URL") . "/orders/" . $order->id
		];

		$tokens = FCMToken::get()->pluck('token')->toArray();

		try {
			Firebase::sendNotification($tokens, $payload);
		} catch (\Throwable $th) {
			Log::info($th->getMessage() . " : " . $th->getTraceAsString());
		}

		return true;
	}
}
