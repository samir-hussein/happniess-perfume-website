@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">

    @if (app()->getLocale() === 'ar')
        <style>
            .shipping-method input[type="radio"] {
                margin-left: 15px;
            }

            .order-item p {
                text-align: right;
            }

            .approve-replacement-policy input[type="checkbox"] {
                margin-left: 10px;
                margin-right: 0px;
            }
        </style>
    @endif
@endsection

@section('content')
    <!-- Checkout Content -->
    <div class="container">
        <!-- Checkout Form -->
        <form action="{{ route('checkout.place-order', app()->getLocale()) }}" method="POST">
            <div class="checkout-container">
                @csrf
                <div class="checkout-form">
                    <h2>{{ __('Checkout') }}</h2>

                    <div class="checkout-section">
                        <h3>{{ __('Shipping Information') }}</h3>
                        <div class="form-group">
                            <label for="full-name">{{ __('Full Name') }}</label>
                            <input type="text" id="full-name" name="name" required
                                value="{{ old('name') ?? Auth::user()->name }}">
                        </div>
                        <div class="form-group">
                            <label for="address">{{ __('Address') }}</label>
                            <input type="text" id="address" name="address" required
                                value="{{ old('address') ?? Auth::user()->address }}">
                        </div>
                        <div class="form-group">
                            <label for="governorate">{{ __('Governorate') }}</label>
                            <select id="governorate" name="city" required>
                                <option value="">{{ __('Select Governorate') }}</option>
                                <option value="cairo"
                                    {{ (old('city') === 'cairo' ? 'selected' : Auth::user()->city === 'cairo') ? 'selected' : '' }}>
                                    {{ __('Cairo') }}</option>
                                <option value="giza"
                                    {{ (old('city') === 'giza' ? 'selected' : Auth::user()->city === 'giza') ? 'selected' : '' }}>
                                    {{ __('Giza') }}</option>
                                <option value="alexandria"
                                    {{ (old('city') === 'alexandria' ? 'selected' : Auth::user()->city === 'alexandria') ? 'selected' : '' }}>
                                    {{ __('Alexandria') }}</option>
                                <option value="port_said"
                                    {{ (old('city') === 'port_said' ? 'selected' : Auth::user()->city === 'port_said') ? 'selected' : '' }}>
                                    {{ __('Port Said') }}</option>
                                <option value="ismailia"
                                    {{ (old('city') === 'ismailia' ? 'selected' : Auth::user()->city === 'ismailia') ? 'selected' : '' }}>
                                    {{ __('Ismailia') }}</option>
                                <option value="suez"
                                    {{ (old('city') === 'suez' ? 'selected' : Auth::user()->city === 'suez') ? 'selected' : '' }}>
                                    {{ __('Suez') }}</option>
                                <option value="faiyum"
                                    {{ (old('city') === 'faiyum' ? 'selected' : Auth::user()->city === 'faiyum') ? 'selected' : '' }}>
                                    {{ __('Faiyum') }}</option>
                                <option value="matruh"
                                    {{ (old('city') === 'matruh' ? 'selected' : Auth::user()->city === 'matruh') ? 'selected' : '' }}>
                                    {{ __('Matruh') }}</option>
                                <option value="beheira"
                                    {{ (old('city') === 'beheira' ? 'selected' : Auth::user()->city === 'beheira') ? 'selected' : '' }}>
                                    {{ __('Beheira') }}</option>
                                <option value="beni_suef"
                                    {{ (old('city') === 'beni_suef' ? 'selected' : Auth::user()->city === 'beni_suef') ? 'selected' : '' }}>
                                    {{ __('Beni Suef') }}</option>
                                <option value="damietta"
                                    {{ (old('city') === 'damietta' ? 'selected' : Auth::user()->city === 'damietta') ? 'selected' : '' }}>
                                    {{ __('Damietta') }}</option>
                                <option value="dakahlia"
                                    {{ (old('city') === 'dakahlia' ? 'selected' : Auth::user()->city === 'dakahlia') ? 'selected' : '' }}>
                                    {{ __('Dakahlia') }}</option>
                                <option value="gharbia"
                                    {{ (old('city') === 'gharbia' ? 'selected' : Auth::user()->city === 'gharbia') ? 'selected' : '' }}>
                                    {{ __('Gharbia') }}</option>
                                <option value="kafr_el_sheikh"
                                    {{ (old('city') === 'kafr_el_sheikh' ? 'selected' : Auth::user()->city === 'kafr_el_sheikh') ? 'selected' : '' }}>
                                    {{ __('Kafr El Sheikh') }}</option>
                                <option value="monufia"
                                    {{ (old('city') === 'monufia' ? 'selected' : Auth::user()->city === 'monufia') ? 'selected' : '' }}>
                                    {{ __('Monufia') }}</option>
                                <option value="qalyubia"
                                    {{ (old('city') === 'qalyubia' ? 'selected' : Auth::user()->city === 'qalyubia') ? 'selected' : '' }}>
                                    {{ __('Qalyubia') }}</option>
                                <option value="sharqia"
                                    {{ (old('city') === 'sharqia' ? 'selected' : Auth::user()->city === 'sharqia') ? 'selected' : '' }}>
                                    {{ __('Sharqia') }}</option>
                                <option value="asyut"
                                    {{ (old('city') === 'asyut' ? 'selected' : Auth::user()->city === 'asyut') ? 'selected' : '' }}>
                                    {{ __('Asyut') }}</option>
                                <option value="minya"
                                    {{ (old('city') === 'minya' ? 'selected' : Auth::user()->city === 'minya') ? 'selected' : '' }}>
                                    {{ __('Minya') }}</option>
                                <option value="qena"
                                    {{ (old('city') === 'qena' ? 'selected' : Auth::user()->city === 'qena') ? 'selected' : '' }}>
                                    {{ __('Qena') }}</option>
                                <option value="sohag"
                                    {{ (old('city') === 'sohag' ? 'selected' : Auth::user()->city === 'sohag') ? 'selected' : '' }}>
                                    {{ __('Sohag') }}</option>
                                <option value="luxor"
                                    {{ (old('city') === 'luxor' ? 'selected' : Auth::user()->city === 'luxor') ? 'selected' : '' }}>
                                    {{ __('Luxor') }}</option>
                                <option value="aswan"
                                    {{ (old('city') === 'aswan' ? 'selected' : Auth::user()->city === 'aswan') ? 'selected' : '' }}>
                                    {{ __('Aswan') }}</option>
                                <option value="new_valley"
                                    {{ (old('city') === 'new_valley' ? 'selected' : Auth::user()->city === 'new_valley') ? 'selected' : '' }}>
                                    {{ __('New Valley') }}</option>
                                <option value="north_sinai"
                                    {{ (old('city') === 'north_sinai' ? 'selected' : Auth::user()->city === 'north_sinai') ? 'selected' : '' }}>
                                    {{ __('North Sinai') }}</option>
                                <option value="south_sinai"
                                    {{ (old('city') === 'south_sinai' ? 'selected' : Auth::user()->city === 'south_sinai') ? 'selected' : '' }}>
                                    {{ __('South Sinai') }}</option>
                                <option value="red_sea"
                                    {{ (old('city') === 'red_sea' ? 'selected' : Auth::user()->city === 'red_sea') ? 'selected' : '' }}>
                                    {{ __('Red Sea') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="phone">{{ __('Phone Number') }}</label>
                            <input type="tel" id="phone" name="phone" required
                                value="{{ old('phone') ?? Auth::user()->phone }}">
                        </div>
                    </div>

                    <div class="checkout-section">
                        <h3>{{ __('Shipping Method') }}</h3>
                        <div class="shipping-methods">
                            @foreach ($shippingMethods as $shippingMethod)
                                <div class="shipping-method">
                                    <input data-cost="{{ $shippingMethod->cost }}"
                                        data-minimum_order_amount="{{ $shippingMethod->minimum_order_amount }}"
                                        type="radio" id="{{ str_replace([' ', '&'], '', $shippingMethod->name_en) }}"
                                        disabled>
                                    <div class="shipping-method-details">
                                        <div class="shipping-method-header">
                                            <label
                                                for="{{ str_replace(' ', '', $shippingMethod->name_en) }}">{{ $shippingMethod->{'name_' . app()->getLocale()} }}</label>
                                            <span class="shipping-price">{{ $shippingMethod->cost }}
                                                {{ __('EGP') }}</span>
                                        </div>
                                        <p class="shipping-estimate">
                                            {{ $shippingMethod->{'delivery_time_' . app()->getLocale()} }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <h2>{{ __('Order Summary') }}</h2>

                    <div class="order-items" id="order-items">

                    </div>

                    <div class="order-item">
                        <span>{{ __('Subtotal') }}</span>
                        <span id="subtotal" data-value="0">0 {{ __('EGP') }}</span>
                    </div>

                    <!-- Add this new line for discounts -->
                    <div class="order-summary-line discount-line" id="discount-line">
                        <span>{{ __('Discount') }} <span id="discount-description"></span></span>
                        <span id="discount-amount">- <span id="discount-amount-value" data-value="0"></span></span>
                    </div>

                    <div class="order-item">
                        <span>{{ __('Shipping') }}</span>
                        <span id="shipping" data-value="">0 {{ __('EGP') }}</span>
                    </div>

                    <div class="order-total">
                        <span>{{ __('Total') }}</span>
                        <span id="total">0 {{ __('EGP') }}</span>
                    </div>

                    <div class="coupon-section">
                        <h3>{{ __('Coupon Code') }}</h3>
                        <div class="coupon-input-group">
                            <input type="text" id="coupon-code" name="coupon"
                                placeholder="{{ __('Enter coupon code') }}">
                            <button type="button" id="apply-coupon">{{ __('Apply') }}</button>
                        </div>
                        <p id="coupon-message" class="coupon-message"></p>
                    </div>

                    <div class="payment-methods">
                        <h3>{{ __('Payment Method') }}</h3>
                        <div class="payment-options">
                            <!-- Credit Card Option -->
                            <div class="payment-option" data-payment="card">
                                <input type="radio" id="card" name="payment" value="card">
                                <div class="payment-content">
                                    <div class="payment-icon">
                                        <i class="far fa-credit-card"></i>
                                    </div>
                                    <div class="payment-details">
                                        <label for="card">{{ __('Credit/Debit Card') }}</label>
                                        <div class="card-icons">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/2560px-Visa_Inc._logo.svg.png"
                                                alt="Visa">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png"
                                                alt="Mastercard">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Wallet Option -->
                            <div class="payment-option" data-payment="wallet">
                                <input type="radio" id="wallet" name="payment" value="wallet">
                                <div class="payment-content">
                                    <div class="payment-icon">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                    <div class="payment-details">
                                        <label for="wallet">{{ __('Mobile Wallet') }}</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Cash on Delivery Option -->
                            <div class="payment-option" data-payment="cash">
                                <input type="radio" id="cash" name="payment" value="cash_on_delivery">
                                <div class="payment-content">
                                    <div class="payment-icon">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div class="payment-details">
                                        <label for="cash">{{ __('Cash on Delivery') }}</label>
                                        <p class="payment-note">{{ __('Pay when you receive your order') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- approve replacement policy -->
                    <div class="approve-replacement-policy">
                        <input type="checkbox" id="approve-replacement-policy" required
                            name="approve_replacement_policy">
                        <label for="approve-replacement-policy">{{ __('I agree to the') }}
                            <a href="{{ route('return-policy', app()->getLocale()) }}" rel="noopener noreferrer"
                                target="_blank">{{ __('Return Policy') }}</a> &
                            <a href="{{ route('shipping-policy', app()->getLocale()) }}" rel="noopener noreferrer"
                                target="_blank">{{ __('Shipping Policy') }}</a>
                        </label>
                    </div>

                    <button type="submit" class="place-order-btn">{{ __('Place Order') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        getLoggedUserCartProducts();

        // Load cart items from localStorage and setup checkout
        document.addEventListener('DOMContentLoaded', () => {
            const governorateSelect = document.getElementById('governorate');

            // Shipping method elements
            const cairoGizaShipping = document.getElementById('CairoGiza');
            const otherGovernoratesShipping = document.getElementById('OtherGovernorates');
            const remoteAreasShipping = document.getElementById('RemoteAreas');

            // Define which governorates belong to which shipping method
            const cairoGizaGovernorates = ['cairo', 'giza'];
            const remoteAreasGovernorates = ['matruh', 'new_valley', 'north_sinai', 'south_sinai', 'red_sea'];

            // chooseShippingMethod(governorateSelect.value);
            chooseShippingMethod(governorateSelect.value, true);

            // Governorate change handler
            governorateSelect.addEventListener('change', function() {
                const selectedGovernorate = this.value;
                chooseShippingMethod(selectedGovernorate);
            });

            function chooseShippingMethod(selectedGovernorate, withoutCost = false) {
                // Reset all shipping methods
                cairoGizaShipping.checked = false;
                otherGovernoratesShipping.checked = false;
                remoteAreasShipping.checked = false;

                cairoGizaShipping.disabled = true;
                otherGovernoratesShipping.disabled = true;
                remoteAreasShipping.disabled = true;

                if (!selectedGovernorate) {
                    if (!withoutCost) updateShippingCost(0);
                    return;
                };

                // Enable and select appropriate shipping method
                if (cairoGizaGovernorates.includes(selectedGovernorate)) {
                    cairoGizaShipping.disabled = false;
                    cairoGizaShipping.checked = true;
                    if (!withoutCost) {
                        if (cairoGizaShipping.dataset.minimum_order_amount && parseFloat(document.getElementById(
                                'subtotal').dataset.value) >= parseFloat(cairoGizaShipping.dataset
                                .minimum_order_amount)) {
                            updateShippingCost(0);
                        } else {
                            updateShippingCost(cairoGizaShipping.dataset.cost);
                        }
                    }
                } else if (remoteAreasGovernorates.includes(selectedGovernorate)) {
                    remoteAreasShipping.disabled = false;
                    remoteAreasShipping.checked = true;
                    if (!withoutCost) {
                        if (remoteAreasShipping.dataset.minimum_order_amount && parseFloat(document.getElementById(
                                'subtotal').dataset.value) >= parseFloat(remoteAreasShipping.dataset
                                .minimum_order_amount)) {
                            updateShippingCost(0);
                        } else {
                            updateShippingCost(remoteAreasShipping.dataset.cost);
                        }
                    }
                } else {
                    otherGovernoratesShipping.disabled = false;
                    otherGovernoratesShipping.checked = true;
                    if (!withoutCost) {
                        if (otherGovernoratesShipping.dataset.minimum_order_amount && parseFloat(document
                                .getElementById(
                                    'subtotal').dataset.value) >= parseFloat(otherGovernoratesShipping.dataset
                                .minimum_order_amount)) {
                            updateShippingCost(0);
                        } else {
                            updateShippingCost(otherGovernoratesShipping.dataset.cost);
                        }
                    }
                }
            }

            // Update shipping cost and totals
            function updateShippingCost(cost) {
                const discountElement = document.getElementById('discount-amount-value');
                const subtotal = document.getElementById('subtotal').dataset.value;
                const shippingElement = document.getElementById('shipping');

                // // Update shipping display
                shippingElement.innerHTML = cost + ' ' + window.translations.egp;
                shippingElement.dataset.value = cost;

                // // Update total
                const total = parseFloat(subtotal) + parseFloat(cost) - parseFloat(discountElement.dataset.value);
                const formatted = formatNumber(total);

                document.getElementById('total').innerHTML = formatted + ' ' + window.translations.egp;
            }

            // Payment Method Selection
            document.querySelectorAll('.payment-option').forEach(option => {
                option.addEventListener('click', function() {
                    // Remove selected class from all options
                    document.querySelectorAll('.payment-option').forEach(opt => {
                        opt.classList.remove('selected');
                    });

                    // Add selected class to clicked option
                    this.classList.add('selected');

                    // Check the radio input
                    const radioInput = this.querySelector('input[type="radio"]');
                    radioInput.checked = true;

                    // Hide all payment forms
                    document.querySelectorAll('.payment-form').forEach(form => {
                        form.style.display = 'none';
                    });
                });
            });

            // Format card number input
            document.getElementById('card-number')?.addEventListener('input', function(e) {
                this.value = this.value.replace(/\s/g, '').replace(/(\d{4})/g, '$1 ').trim();
            });

            // Format expiry date input
            document.getElementById('card-expiry')?.addEventListener('input', function(e) {
                this.value = this.value.replace(/\D/g, '').replace(/(\d{2})(\d{0,2})/, '$1/$2');
            });

            const applyCouponButton = document.getElementById('apply-coupon');
            applyCouponButton.addEventListener('click', function() {
                const couponCode = document.getElementById('coupon-code').value;
                fetch('{{ route('checkout.apply-coupon', app()->getLocale()) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute(
                                    'content')
                        },
                        body: JSON.stringify({
                            code: couponCode
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            // Handle HTTP error statuses (e.g., 400, 403, 422, 500, etc.)
                            return response.json().then(err => {
                                showToast(err.message, 'error');
                                const discountElement = document.getElementById(
                                    'discount-amount-value');
                                const subtotal = document.getElementById('subtotal').dataset
                                    .value;
                                const shippingElement = document.getElementById('shipping');

                                discountElement.innerHTML = 0 + ' ' + window
                                    .translations.egp;
                                discountElement.dataset.value = 0;

                                // // Update total
                                const total = parseFloat(subtotal) + parseFloat(shippingElement
                                    .dataset.value);
                                const formatted = formatNumber(total);

                                document.getElementById('total').innerHTML = formatted + ' ' +
                                    window
                                    .translations.egp;
                            });
                        }
                        return response.json(); // Proceed if response is OK (status 200-299)
                    }).then(data => {
                        if (data.discount) {
                            const discountElement = document.getElementById('discount-amount-value');
                            const subtotal = document.getElementById('subtotal').dataset.value;
                            const shippingElement = document.getElementById('shipping');

                            discountElement.innerHTML = data.discount + ' ' + window.translations.egp;
                            discountElement.dataset.value = data.discount;

                            // // Update total
                            const total = parseFloat(subtotal) + parseFloat(shippingElement.dataset
                                    .value) -
                                parseFloat(discountElement.dataset.value);
                            const formatted = formatNumber(total);

                            document.getElementById('total').innerHTML = formatted + ' ' + window
                                .translations.egp;
                        } else {
                            showToast(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
            });
        })
    </script>
@endsection
