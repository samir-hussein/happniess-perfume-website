<?php

namespace App\Repositories;

use App\Models\OrderLog;
use App\Interfaces\IOrderLogRepo;
use App\Repositories\BaseRepository;

class OrderLogRepo extends BaseRepository implements IOrderLogRepo
{
    public function __construct(OrderLog $orderLog)
    {
        parent::__construct($orderLog);
    }

    public function getOrderLogs(int $orderId)
    {
        return $this->model->where('order_id', $orderId)->with('order')->latest("id")->get();
    }
}
