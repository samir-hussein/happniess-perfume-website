<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:sanctum');

Route::post("/test/webhook_json", function (Request $request) {
	Log::info("post");
	Log::info($request->all());
});

Route::get("/test/webhook_json", function (Request $request) {
	Log::info("get");
	Log::info($request->all());
});
