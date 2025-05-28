<?php

namespace App\Http\Controllers;

use App\Interfaces\IShippingMethodService;

class ShippingPolicyController extends Controller
{
	public function __construct(
		private IShippingMethodService $shippingMethodService
	) {}

	public function index()
	{
		return view('shipping-policy', [
			'shippingMethods' => $this->shippingMethodService->getAll()
		]);
	}
}
