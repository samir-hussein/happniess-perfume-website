<?php

namespace App\Handlers;

use App\Interfaces\IOrderRepo;
use App\Facades\PaymentGateway;
use Illuminate\Support\Facades\Log;

class PaymentGatewayWebhookHandler
{
    public function __construct(private IOrderRepo $orderRepo) {}

    public function __invoke(array $data)
    {
        Log::info(json_encode($data));
        if ($data['referenceNumber'] == NULL) {
            return response()->json([
                'success' => false,
                'message' => 'Reference number is null',
            ], 400);
        }

        if (env("FAWATERK_API_KEY") != $data['api_key']) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid api key',
            ], 400);
        }

        // if (!PaymentGateway::verifyPayment($data['invoice_id'], $data['invoice_key'], $data['payment_method'], $data['hashKey'])) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Invalid payment',
        //     ], 400);
        // }

        if ($data['invoice_status'] == 'paid') {
            $invoiceDetails = PaymentGateway::getInvoiceDetails($data['invoice_id']);

            $orderNumber = $invoiceDetails->invoice_number;
            $paymentMethod = $invoiceDetails->payment_method;
            $paid = $invoiceDetails->paid;
            $statusText = $invoiceDetails->status_text;
            $paidAt = $invoiceDetails->paid_at;
            $amount = $invoiceDetails->total_paid;

            if ($paid != 1 || $statusText != 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid invoice status',
                ], 400);
            }

            $order = $this->orderRepo->getOrderByNumberAndInvoiceId($orderNumber, $invoiceDetails->invoice_id);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found',
                ], 404);
            }

            if ($order->total_price != (float)$amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid amount',
                ], 400);
            }

            $orderDataToUpdate = [
                'payment_status' => 'paid',
                'payment_method' => $this->paymentMethodsNamesMatch($paymentMethod),
                'paid_at' => $paidAt,
                'reference_number' => $data['referenceNumber'],
            ];

            if ($paymentMethod == 'Mobile Wallet') {
                $orderDataToUpdate['payment_link'] = env('FAWATERK_INVOICE_URL') . '/' . $order->invoice_id . '/' . $order->invoice_key;
            }

            $order->update($orderDataToUpdate);

            $data = [
                "payment_status" => "paid",
                "action_ar" => "تم الدفع",
                "action_en" => "Payment received",
                "description_ar" => "تم الدفع بنجاح",
                "description_en" => "Payment received successfully",
            ];

            $this->orderRepo->updatePaymentStatus($order->id, $data);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful'
            ], 200);
        }
    }

    private function paymentMethodsNamesMatch($paymentMethod)
    {
        return match ($paymentMethod) {
            'Card' => 'card',
            'Mobile Wallet' => 'wallet',
            default => 'card',
        };
    }
}
