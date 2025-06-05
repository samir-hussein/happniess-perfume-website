<?php

namespace App\Interfaces;

interface IOrderRepo extends IRepository
{
    public function createOrder(array $order, array $orderItems);
    public function countOrdersByStatus(int $clientId);
    public function getOrders(int $clientId, string|null $status, string|null $search, int $page = 1, int $perPage = 3);
    public function cancelOrder(int $orderId);
    public function getOrderByNumberAndInvoiceId(string $orderNumber, int $invoiceId);
    public function updatePaymentStatus(int $id, array $data);
}
