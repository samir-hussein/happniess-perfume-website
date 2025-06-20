<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ProductPageController;
use App\Http\Controllers\WalletPaymentController;
use App\Http\Controllers\ShippingPolicyController;
use App\Http\Controllers\PusherController;
use App\Http\Controllers\MessageController;

Route::post("/pusher/auth", [PusherController::class, "auth"])->name("pusher.auth");

Route::get('/', [HomePageController::class, "index"])->middleware('local')->name("home");
Route::post('{locale}/cart', [CartController::class, "getCartProducts"])->name("cart")->middleware(['local', 'throttle:100,1']);

Route::post("send-message", [MessageController::class, "sendMessage"])->name("send-message");
Route::post("mark-as-read", [MessageController::class, "markAsRead"])->name("mark-as-read");

// Route to redirect to Google's OAuth page
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');

// Route to handle the callback from Google
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

Route::get('/auth/logout', [AuthController::class, 'logout'])->middleware('auth')->name('auth.logout');

Route::middleware(['local', 'guest'])->prefix('{locale}')->group(function () {
	Route::get('/login', function () {
		return view('login');
	})->name('login');

	Route::get('/register', function () {
		return view('register');
	})->name('register');
});

Route::middleware(['guest', 'throttle:6,10'])->group(function () {
	Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
	Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
});

Route::middleware(['local'])->prefix('{locale}')->group(function () {
	Route::get('/', [HomePageController::class, "index"])->name("home");
	Route::get('/products', [HomePageController::class, "products"])->name("products");
	Route::get('/product/{id}/size/{size}', [ProductPageController::class, "index"])->name("product");
	Route::get('/return-policy', function () {
		return view('return-policy');
	})->name("return-policy");
	Route::get('/shipping-policy', [ShippingPolicyController::class, "index"])->name("shipping-policy");
});

Route::middleware(['local', 'auth'])->prefix('{locale}')->group(function () {
	Route::get('/favorite', [FavoriteController::class, "index"])->name("favorite");
	Route::get('/checkout', [CheckOutController::class, "index"])->name("checkout");
	Route::get('/order-confirmation', [CheckOutController::class, "orderConfirmation"])->name("order-confirmation");
	Route::get('/payment-failed', [CheckOutController::class, "paymentFailed"])->name("payment-failed");
	Route::get('/order', [OrderController::class, "index"])->name("order.index");
	Route::get('/order/logs/{orderId}', [OrderController::class, "getOrderLogs"])->name("order.logs");
	Route::get('/wallet-payment', [WalletPaymentController::class, "showQrCode"])->name("wallet-payment");

	Route::middleware('throttle:60,10')->group(function () {
		Route::post('/checkout/apply-coupon', [CheckOutController::class, "applyCoupon"])->name("checkout.apply-coupon");
		Route::post('/checkout/place-order', [CheckOutController::class, "placeOrder"])->name("checkout.place-order");
		Route::post('/order/reorder/{orderId}', [OrderController::class, "reorder"])->name("order.reorder");
		Route::put('/order/cancel/{orderId}', [OrderController::class, "cancelOrder"])->name("order.cancel");
		Route::post('/buy-now', [OrderController::class, "buyNow"])->name("buy.now");
	});
});

Route::middleware('auth')->group(function () {
	Route::get('/cart/count', [CartController::class, "getCartCount"])->name("cart.count");
	Route::get('/favorite/count', [FavoriteController::class, "getFavoritesCount"])->name("favorite.count");
	Route::get("/order/{orderId}/invoice", [OrderController::class, "getInvoice"])->name("order.invoice");
	Route::get("/check-payment-status/{orderId}", [OrderController::class, "checkPaymentStatus"])->name("check.payment.status");

	Route::middleware('throttle:60,10')->group(function () {
		Route::post('/cart/add', [CartController::class, "addToCart"])->name("cart.add");
		Route::delete('/cart/remove', [CartController::class, "removeFromCart"])->name("cart.remove");
		Route::post('/cart/update', [CartController::class, "updateCartQuantity"])->name("cart.update");
		Route::post('/favorite/add', [FavoriteController::class, "addToFavorite"])->name("favorite.add");
		Route::post('/cart/sync', [CartController::class, "syncCart"])->name("cart.sync");
	});
});
