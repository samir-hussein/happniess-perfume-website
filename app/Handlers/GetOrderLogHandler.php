<?php

namespace App\Handlers;

use App\Interfaces\IOrderLogRepo;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Auth;

class GetOrderLogHandler
{
	public function __construct(private IOrderLogRepo $orderLogRepo) {}

	public function __invoke(int $orderId)
	{
		$orderLogs = $this->orderLogRepo->getOrderLogs($orderId);
		$order = $orderLogs->first()->order;

		if ($order->client_id != Auth::user()->id) {
			throw new GeneralException("You are not authorized to view this order");
		}

		$progress = [
			"pending" => "25%",
			"processing" => "50%",
			"shipped" => "75%",
			"delivered" => "100%",
		];

		$icons = [
			'Order Placed' => 'shopping-cart',
			'Order Processing' => 'cog',
			'Order Shipped' => 'truck',
			'Order Delivered' => 'check',
			'Order Cancelled' => 'times',
			'Payment received' => 'credit-card',
			'Order Reshipment' => 'truck',
		];

		return [
			"orderLogs" => $orderLogs,
			"order" => $order,
			"statusDates" => [
				"pending" => $orderLogs->where("action_en", "Order Placed")->first()?->created_at->format("M d, Y"),
				"processing" => $orderLogs->where("action_en", "Order Processing")->first()?->created_at->format("M d, Y"),
				"shipped" => $orderLogs->whereIn("action_en", ["Order Shipped", "Order Reshipment"])->sortByDesc("created_at")->first()?->created_at->format("M d, Y"),
				"delivered" => $orderLogs->where("action_en", "Order Delivered")->first()?->created_at->format("M d, Y"),
			],
			"progress" => $progress[$order->order_status],
			"icons" => $icons,
		];
	}
}
