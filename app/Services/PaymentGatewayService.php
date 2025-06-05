<?php

namespace App\Services;

use App\Interfaces\IPaymentService;
use Illuminate\Support\Facades\Http;

class PaymentGatewayService implements IPaymentService
{
    protected string $url;
    protected string $getInvoiceDataUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->url = env("FAWATERK_PAY_URL");
        $this->getInvoiceDataUrl = env("FAWATERK_GET_INVOICE_DATA_URL");
        $this->apiKey = env("FAWATERK_API_KEY");
    }

    public function pay(array $order, array $customer, array $cartItems)
    {
        $orderEncrypted =  encrypt(json_encode($order));

        $data = [
            "payment_method_id" => $this->paymentMethodsNamesMatch($order['payment_method']),
            "cartTotal" => $order['total_price_number'],
            "currency" => "EGP",
            "invoice_number" => $order['order_number'],
            "customer" => $customer,
            "cartItems" => $cartItems,
            "redirectionUrls" => [
                "successUrl" => route("order-confirmation", ["locale" => app()->getLocale(), "order" => $orderEncrypted]),
                "failUrl" => route("payment-failed", ["locale" => app()->getLocale(), "order" => $orderEncrypted]),
                "pendingUrl" => route("order-confirmation", ["locale" => app()->getLocale(), "order" => $orderEncrypted]),
            ],
            "sendSMS" => true,
            "lang" => app()->getLocale(),
            "discountData" => [
                "type" => "literal",
                "value" => $order['discount_value'],
            ]
        ];

        $response = Http::withHeaders([
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . $this->apiKey,
        ])->post($this->url, $data);

        if ($response->successful()) {
            $responseData = $response->json();
            // Convert to object
            $responseObject = json_decode(json_encode($responseData));
            return isset($responseObject->data) ? $responseObject->data : $responseObject;
        } else {
            throw new \Exception("Failed to pay : " . json_encode($response->json()));
        }
    }

    private function paymentMethodsNamesMatch($paymentMethod)
    {
        return match ($paymentMethod) {
            'Card' => 2,
            'Wallet' => 4,
            default => 2,
        };
    }

    private function generateHashKey(int $invoiceId, string $invoiceKey, string $paymentMethod)
    {
        $secretKey = $this->apiKey;
        $queryParam = "InvoiceId=$invoiceId&InvoiceKey=$invoiceKey&PaymentMethod=$paymentMethod";
        return hash_hmac('sha256', $queryParam, $secretKey, false);
    }

    public function verifyPayment(int $invoiceId, string $invoiceKey, string $paymentMethod, string $hash)
    {
        $generatedHash = $this->generateHashKey($invoiceId, $invoiceKey, $paymentMethod);
        return $generatedHash === $hash;
    }

    public function getInvoiceDetails(int $invoiceId)
    {
        $response = Http::withHeaders([
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . $this->apiKey,
        ])->get($this->getInvoiceDataUrl . "/" . $invoiceId);

        if ($response->successful()) {
            $responseData = $response->json();
            // Convert to object
            $responseObject = json_decode(json_encode($responseData));
            return isset($responseObject->data) ? $responseObject->data : $responseObject;
        } else {
            throw new \Exception("Failed to get invoice details : " . json_encode($response->json()));
        }
    }
}
