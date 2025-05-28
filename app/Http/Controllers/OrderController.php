<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function index()
    {
        return view('order', [
            "ordersCount" => $this->orderService->countOrdersByStatus(Auth::user()->id),
            "orders" => $this->orderService->getOrders(Auth::user()->id, request()),
        ]);
    }

    public function getOrderLogs(string $locale, int $orderId)
    {
        $data = $this->orderService->getOrderLogs($orderId);
        return view("ajax-components.order-log", $data)->render();
    }

    public function cancelOrder(string $locale, int $orderId)
    {
        $this->orderService->cancelOrder($orderId);
        return redirect()->back()->with('success', __('Order cancelled successfully'));
    }

    public function reorder(string $locale, int $orderId)
    {
        $this->orderService->reorder($orderId);
        return redirect()->route('checkout', [$locale]);
    }
}
