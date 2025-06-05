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

    <div id="browseBtn" class="browse-btn">
        <p>{{ __('There are no products in your cart') }}</p>
        <a href="{{ route('products', app()->getLocale()) }}" class="checkout-btn">{{ __('Browse Products') }}</a>
    </div>

</div>
