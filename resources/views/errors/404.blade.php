@extends('layouts.app')

@section('title', 'Page Not Found | Happniess Perfume | عطور السعادة')

@section('content')
    <div class="error-page">
        <div class="container">
            <div class="error-content">
                <div class="error-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <h1>404</h1>
                <h2>{{ __('Page Not Found') }}</h2>
                <p>{{ __('The page you are looking for does not exist or has been moved.') }}</p>
                <div class="error-actions">
                    <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="return-btn">
                        <i class="fas fa-home"></i> {{ __('Return to Home') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
