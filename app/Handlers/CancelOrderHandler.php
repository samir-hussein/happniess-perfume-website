<?php

namespace App\Handlers;

use App\Exceptions\GeneralException;
use App\Interfaces\IOrderRepo;

class CancelOrderHandler
{
    public function __construct(private IOrderRepo $orderRepo) {}

    public function __invoke(int $orderId)
    {
        $order = $this->orderRepo->getById($orderId);

        if ($order->order_status != 'pending') {
            throw new GeneralException("You can't cancel this order");
        }

        return $this->orderRepo->cancelOrder($orderId);
    }
}
