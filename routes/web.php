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
use App\Http\Controllers\ShippingPolicyController;

Route::get('/', [HomePageController::class, "index"])->middleware('local')->name("home");
Route::post('/cart', [CartController::class, "getCartProducts"])->name("cart");

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

Route::middleware('guest')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
});

Route::middleware(['local'])->prefix('{locale}')->group(function () {
    Route::get('/', [HomePageController::class, "index"])->name("home");
    Route::get('/product/{id}/size/{size}', [ProductPageController::class, "index"])->name("product");
    Route::get('/return-policy', function () {
        return view('return-policy');
    })->name("return-policy");
    Route::get('/shipping-policy', [ShippingPolicyController::class, "index"])->name("shipping-policy");
});

Route::middleware(['local', 'auth'])->prefix('{locale}')->group(function () {
    Route::get('/favorite', [FavoriteController::class, "index"])->name("favorite");
    Route::get('/checkout', [CheckOutController::class, "index"])->name("checkout");
    Route::post('/checkout/apply-coupon', [CheckOutController::class, "applyCoupon"])->name("checkout.apply-coupon");
    Route::post('/checkout/place-order', [CheckOutController::class, "placeOrder"])->name("checkout.place-order");
    Route::get('/order-confirmation', [CheckOutController::class, "orderConfirmation"])->name("order-confirmation");
    Route::get('/order', [OrderController::class, "index"])->name("order.index");
    Route::get('/order/logs/{orderId}', [OrderController::class, "getOrderLogs"])->name("order.logs");
    Route::put('/order/cancel/{orderId}', [OrderController::class, "cancelOrder"])->name("order.cancel");
    Route::post('/order/reorder/{orderId}', [OrderController::class, "reorder"])->name("order.reorder");
});

Route::middleware('auth')->group(function () {
    Route::post('/cart/add', [CartController::class, "addToCart"])->name("cart.add");
    Route::get('/cart/count', [CartController::class, "getCartCount"])->name("cart.count");
    Route::delete('/cart/remove', [CartController::class, "removeFromCart"])->name("cart.remove");
    Route::post('/cart/update', [CartController::class, "updateCartQuantity"])->name("cart.update");
    Route::post('/cart/sync', [CartController::class, "syncCart"])->name("cart.sync");
    Route::post('/favorite/add', [FavoriteController::class, "addToFavorite"])->name("favorite.add");
    Route::get('/favorite/count', [FavoriteController::class, "getFavoritesCount"])->name("favorite.count");
});
