<?php

namespace App\Interfaces;

interface IPaymentService
{
	public function pay(array $order, array $customer, array $cartItems);
	public function getInvoiceDetails(int $invoiceId);
	public function verifyPayment(int $invoiceId, string $invoiceKey, string $paymentMethod, string $hash);
}
