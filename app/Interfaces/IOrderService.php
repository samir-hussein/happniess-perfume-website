<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface IOrderService
{
	public function countOrdersByStatus(int $clientId);
	public function getOrders(int $clientId, Request $request);
	public function getOrderLogs(int $orderId);
	public function cancelOrder(int $orderId);
	public function reorder(int $orderId);
	public function getInvoice(int $orderId);
	public function checkPaymentStatus(int $orderId);
}
