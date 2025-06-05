<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Interfaces\IOrderRepo;
use App\Handlers\ReorderHandler;
use App\Interfaces\IOrderService;
use App\Handlers\AddToCartHandler;
use App\Handlers\GetInvoiceHandler;
use App\Handlers\CancelOrderHandler;
use App\Handlers\GetOrderLogHandler;
use Illuminate\Support\Facades\Auth;
use App\Handlers\CheckPaymentStatusHandler;

class OrderService implements IOrderService
{
	public function __construct(private IOrderRepo $orderRepo, private CancelOrderHandler $cancelOrderHandler, private GetOrderLogHandler $getOrderLogHandler, private ReorderHandler $reorderHandler, private GetInvoiceHandler $getInvoiceHandler, private CheckPaymentStatusHandler $checkPaymentStatusHandler, private AddToCartHandler $addToCartHandler) {}

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

	public function getInvoice(int $orderId)
	{
		return $this->getInvoiceHandler->__invoke($orderId);
	}

	public function checkPaymentStatus(int $orderId)
	{
		return $this->checkPaymentStatusHandler->__invoke($orderId);
	}

	public function buyNow(array $data)
	{
		$data['quantity'] = 1;
		return $this->addToCartHandler->__invoke($data, Auth::user()->id);
	}
}
