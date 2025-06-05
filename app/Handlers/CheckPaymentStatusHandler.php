<?php

namespace App\Handlers;

use App\Interfaces\IOrderRepo;
use App\Facades\PaymentGateway;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Auth;

class CheckPaymentStatusHandler
{
    public function __construct(private IOrderRepo $orderRepo) {}

    public function __invoke(int $orderId)
    {
        $order = $this->orderRepo->getById($orderId);

        if ($order->client_id != Auth::user()->id) {
            throw new GeneralException("You are not authorized to check this order");
        }

        if (!$order->invoice_id) {
            throw new GeneralException("Invoice not found");
        }

        $invoiceDetails = PaymentGateway::getInvoiceDetails($order->invoice_id);

        $paid = $invoiceDetails->paid;
        $statusText = $invoiceDetails->status_text;

        if ($paid != 1 || $statusText != 'paid') {
            return [
                'success' => false,
            ];
        }

        $orderPreview = [
            "order_id" => $order->id,
            "order_number" => $order->order_number,
            "created_at" => $order->created_at->format('Y-m-d'),
            "total_price" => number_format($order->total_price, 2, '.', ','),
            "total_price_number" => $order->sub_total_price + $order->shipping_cost,
            "payment_method" => $this->paymentMethodsNamesMatch($order->payment_method),
            "discount_value" => $order->discount_amount,
        ];

        return [
            'success' => true,
            'redirect_url' => route('order-confirmation', ['locale' => app()->getLocale(), 'order' => encrypt(json_encode($orderPreview))]),
        ];
    }

    private function paymentMethodsNamesMatch($paymentMethod)
    {
        return match ($paymentMethod) {
            'cash_on_delivery' => 'Cash on delivery',
            'card' => 'Card',
            'wallet' => 'Wallet',
            default => 'Cash on delivery',
        };
    }
}
