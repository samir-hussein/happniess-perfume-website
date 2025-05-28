<?php

namespace App\Services;

use App\Handlers\CancelOrderHandler;
use App\Handlers\GetOrderLogHandler;
use App\Handlers\ReorderHandler;
use App\Interfaces\IOrderRepo;
use App\Interfaces\IOrderService;
use Illuminate\Http\Request;

class OrderService implements IOrderService
{
	public function __construct(private IOrderRepo $orderRepo, private CancelOrderHandler $cancelOrderHandler, private GetOrderLogHandler $getOrderLogHandler, private ReorderHandler $reorderHandler) {}

	public function countOrdersByStatus(int $clientId)
	{
		return $this->orderRepo->countOrdersByStatus($clientId);
	}

	public function getOrders(int $clientId, Request $request)
	{
		return $this->orderRepo->getOrders($clientId, $request->status, $request->search, $request->page ?? 1, $request->limit ?? 3);
	}

	public function cancelOrder(int $orderId)
	{
		return $this->cancelOrderHandler->__invoke($orderId);
	}

	public function getOrderLogs(int $orderId)
	{
		return $this->getOrderLogHandler->__invoke($orderId);
	}

	public function reorder(int $orderId)
	{
		return $this->reorderHandler->__invoke($orderId);
	}
}
