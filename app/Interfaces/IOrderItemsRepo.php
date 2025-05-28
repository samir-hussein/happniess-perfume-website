<?php

namespace App\Interfaces;

interface IOrderItemsRepo extends IRepository
{
    public function getOrderItems(int $orderId);
}
