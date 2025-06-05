<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentGatewayController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("/fawaterk/webhook_json", [PaymentGatewayController::class, 'webhook'])->middleware('throttle:60,1');
