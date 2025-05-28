<?php

namespace App\Interfaces;

interface ICheckoutService
{
    public function applyCoupon(array $data);

    public function placeOrder(array $data);
}
