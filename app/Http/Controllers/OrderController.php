<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Http\Requests\BuyNowRequest;
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

	public function getInvoice(int $orderId)
	{
		$data = $this->orderService->getInvoice($orderId);

		if (str_contains($data['payment_link'], 'http')) {
			return redirect($data['payment_link']);
		}
		return redirect()->route('wallet-payment', [
			'order' => encrypt(json_encode($data)),
			'locale' => app()->getLocale()
		]);
	}

	public function checkPaymentStatus(int $orderId)
	{
		return $this->orderService->checkPaymentStatus($orderId);
	}

	public function buyNow(string $locale, BuyNowRequest $request)
	{
		$this->orderService->buyNow($request->validated());
		return redirect()->route('checkout', [$locale]);
	}
}
