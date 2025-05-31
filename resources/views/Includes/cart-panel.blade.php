<div class="cart-panel">
    <div class="cart-header">
        <h3>{{ __('Your Cart') }}</h3>
        <i class="fas fa-times close-cart"></i>
    </div>

    <div class="cart-items">
        <!-- Cart items will be added here dynamically -->
    </div>

    <div class="cart-total" style="display: none;">
        <span>{{ __('Total') }}:</span>
        <span id="cartTotal">0</span>
    </div>

    <a href="{{ route('checkout', app()->getLocale()) }}" class="checkout-btn"
        style="display: none;">{{ __('Proceed to Checkout') }}</a>

    <a href="{{ route('home', app()->getLocale()) }}" class="checkout-btn" id="browseBtn"
        style="display: none">{{ __('Browse Products') }}</a>
</div>
