<?php

namespace App\Repositories;

use App\Interfaces\IOrderItemsRepo;
use App\Models\OrderItem;
use App\Repositories\BaseRepository;

class OrderItemsRepo extends BaseRepository implements IOrderItemsRepo
{
    public function __construct(OrderItem $orderItem)
    {
        parent::__construct($orderItem);
    }

    public function getOrderItems(int $orderId)
    {
        return $this->model->where('order_id', $orderId)->get();
    }
}
