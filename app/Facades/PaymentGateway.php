<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PaymentGateway extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return "PaymentGateway";
    }

    public static function pay(array $order, array $customer, array $cartItems)
    {
        return app()->make("PaymentGateway")->pay($order, $customer, $cartItems);
    }

    public static function verifyPayment(int $invoiceId, string $invoiceKey, string $paymentMethod, string $hash)
    {
        return app()->make("PaymentGateway")->verifyPayment($invoiceId, $invoiceKey, $paymentMethod, $hash);
    }

    public static function getInvoiceDetails(int $invoiceId)
    {
        return app()->make("PaymentGateway")->getInvoiceDetails($invoiceId);
    }
}
