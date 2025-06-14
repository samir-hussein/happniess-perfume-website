@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/return-policy.css') }}">
@endsection

@section('content')
    <!-- Return Policy Content -->
    <main class="policy-container">
        <div class="policy-header">
            <h1>{{ __('Return & Refund Policy') }}</h1>
            <p>{{ __('We want you to be completely satisfied with your purchase') }}</p>
        </div>

        <div class="policy-section">
            <h2>{{ __('Our Return Policy') }}</h2>
            <p>{{ __('At Happiness Perfume, we stand behind the quality of our products. The perfume will not be returned nor exchanged unless there is an issue with the atomizer or a leak in the perfume. You may return most items within 7 days of delivery for an exchange.') }}
            </p>
        </div>

        <div class="policy-section">
            <h2>{{ __('How to Return an Item') }}</h2>
            <h3>{{ __('Step 1: Request a Return') }}</h3>
            <p>{{ __('Contact our customer service team on whatsapp at 01011796422 with your order number and reason for return. We\'ll provide you with a Return Authorization Number.') }}
            </p>

            <h3>{{ __('Step 2: Package Your Item') }}</h3>
            <p>{{ __('Securely pack the item in its original packaging, including all accessories and documentation.') }}
            </p>

            <h3>{{ __('Step 3: Ship Your Return') }}</h3>
            <p>{{ __('Send the package to our return center at:') }}</p>
            <address>
                {{ __('Cairo, Manshiyet Nasser, Autostrad Road, Al-Mazlaqan Station') }}<br>
                {{ __('(Include your Return Authorization Number)') }}
            </address>
        </div>

        <div class="policy-section">
            <h2>{{ __('Exchanges Process') }}</h2>
            <p>{{ __('We\'ll ship the replacement item once we receive your return.') }}</p>
        </div>

        <div class="contact-box">
            <h3>{{ __('Need Assistance?') }}</h3>
            <p>{{ __('Our customer service team is happy to help with any questions:') }}</p>

            <div class="contact-info">
                <i class="fab fa-whatsapp"></i>
                <span><a target="_blank" rel="noopener noreferrer" href="https://wa.me/+201011796422">01011796422</a></span>
            </div>
            <div class="contact-info">
                <i class="fas fa-clock"></i>
                <span>{{ __('Every day from 3 PM to 12 AM.') }}</span>
            </div>
        </div>
    </main>
@endsection
