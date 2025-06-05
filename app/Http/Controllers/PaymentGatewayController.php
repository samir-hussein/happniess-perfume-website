<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Handlers\PaymentGatewayWebhookHandler;

class PaymentGatewayController extends Controller
{
	public function __construct(private PaymentGatewayWebhookHandler $webhookHandler) {}

	public function webhook(Request $request)
	{
		return $this->webhookHandler->__invoke($request->all());
	}
}
