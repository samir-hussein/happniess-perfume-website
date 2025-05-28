<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\CheckoutService;
use App\Http\Requests\PlaceOrderRequest;
use App\Http\Requests\ApplyCouponRequest;
use App\Interfaces\IShippingMethodService;

class CheckOutController extends Controller
{
	public function __construct(
		private IShippingMethodService $shippingMethodService,
		private CheckoutService $checkoutService
	) {}

	public function index()
	{
		return view('checkout', [
			'shippingMethods' => $this->shippingMethodService->getAll()
		]);
	}

	public function applyCoupon(ApplyCouponRequest $request)
	{
		try {
			$result = $this->checkoutService->applyCoupon($request->validated());

			return response()->json($result);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => $e->getMessage(),
			]);
		}
	}

	public function placeOrder(PlaceOrderRequest $request)
	{
		try {
			$result = $this->checkoutService->placeOrder($request->validated());

			return redirect()->route('order-confirmation', [
				'order' => encrypt(json_encode($result['order'])),
				'locale' => app()->getLocale()
			]);
		} catch (\Exception $e) {
			return back()->with('error', $e->getMessage());
		}
	}

	public function orderConfirmation()
	{
		if (!request('order')) {
			return redirect()->route('home', app()->getLocale());
		}

		try {
			$order = json_decode(decrypt(request('order')));
		} catch (\Exception $e) {
			return redirect()->route('home', app()->getLocale());
		}

		return view('order-confirmation', [
			'order' => $order,
		]);
	}
}
