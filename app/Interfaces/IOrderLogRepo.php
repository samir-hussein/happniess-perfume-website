<?php

namespace App\Interfaces;

interface IOrderLogRepo extends IRepository
{
    public function getOrderLogs(int $orderId);
}
