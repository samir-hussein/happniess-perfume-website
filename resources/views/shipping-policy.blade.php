@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/shipping.css') }}">

    @if (app()->getLocale() === 'ar')
        <style>
            .policy-section h2:after {
                right: 0;
            }

            .shipping-table th {
                text-align: right;
            }

            .policy-section li:before {
                content: '\f053';
                right: 0;
            }

            .policy-section li {
                padding-right: 25px;
            }
        </style>
    @endif
@endsection

@section('content')
    <!-- Shipping Policy Content -->
    <main class="shipping-policy-container">
        <div class="policy-header">
            <h1>{{ __('Shipping Policy') }}</h1>
            <p>{{ __('We deliver Happniess right to your doorstep') }}</p>
        </div>

        <div class="policy-section">
            <h2>{{ __('Shipping Options & Delivery Times') }}</h2>
            <table class="shipping-table">
                <thead>
                    <tr>
                        <th>{{ __('Shipping Method') }}</th>
                        <th>{{ __('Delivery Time') }}</th>
                        <th>{{ __('Cost') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($shippingMethods as $shippingMethod)
                        <tr>
                            <td><strong>{{ $shippingMethod->{'name_' . app()->getLocale()} }}</strong></td>
                            <td>{{ $shippingMethod->{'delivery_time_' . app()->getLocale()} }}</td>
                            <td>{{ $shippingMethod->cost }} {{ __('EGP') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="policy-section">
            <h2>{{ __('Order Processing') }}</h2>
            <p>{{ __('Here\'s what happens after you place your order:') }}</p>
            <ul>
                <li><strong>{{ __('Order Received:') }}</strong> {{ __('You\'ll receive confirmation immediately') }}</li>
                <li><strong>{{ __('Processing:') }}</strong> {{ __('We prepare your order within 1-2 business days') }}
                </li>
                <li><strong>{{ __('Quality Check:') }}</strong> {{ __('Every perfume is inspected before shipping') }}
                </li>
                <li><strong>{{ __('Shipping:') }}</strong>
                    {{ __('Your item is currently being shipped.') }}</li>
                <li><strong>{{ __('Delivery:') }}</strong> {{ __('Our carrier will deliver your package') }}</li>
            </ul>
        </div>

        <div class="policy-section">
            <h2>{{ __('Important Information') }}</h2>
            <h3>{{ __('Tracking Your Order') }}</h3>
            <p>{{ __('You can track your package using our website.') }}
            </p>

            <h3>{{ __('Undeliverable Packages') }}</h3>
            <p>{{ __('If a package is returned to us as undeliverable, we will contact you to arrange reshipment. Additional shipping fees may apply.') }}
            </p>
        </div>

        <div class="contact-box">
            <h3>{{ __('Need Assistance?') }}</h3>
            <p>{{ __('Our customer service team is happy to help with any questions:') }}</p>

            <div class="contact-info">
                <i class="fab fa-whatsapp"></i>
                <span><a target="_blank" href="https://wa.me/+201011796422">01011796422</a></span>
            </div>
            <div class="contact-info">
                <i class="fas fa-clock"></i>
                <span>{{ __('Every day from 3 PM to 12 AM.') }}</span>
            </div>
        </div>
    </main>
@endsection
