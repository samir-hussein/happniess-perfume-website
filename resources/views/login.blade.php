@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
    <!-- Login Section -->
    <section class="login-section">
        <div class="container">
            <div class="login-container">
                <div class="login-image">
                    <h2>{{ __('Welcome Back') }}</h2>
                    <p>{{ __('Sign in to access your personalized fragrance profile, wishlist, and exclusive member benefits.') }}
                    </p>
                </div>
                <div class="login-form">
                    <h2>{{ __('Sign In') }}</h2>
                    <p>{{ __('Welcome back to Happiness Perfume. Please enter your details.') }}</p>

                    <form id="loginForm" action="{{ route('auth.login') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="email">{{ __('Email Address') }}</label>
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="{{ __('Enter your email') }}" required>
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <div class="form-group password-field">
                            <label for="password">{{ __('Password') }}</label>
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="{{ __('Enter your password') }}" required>
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>

                        <button type="submit" class="btn">{{ __('Sign In') }}</button>
                    </form>

                    <div class="divider">{{ __('or continue with') }}</div>

                    <div class="social-login">
                        <a class="social-btn google-btn" href="{{ route('auth.google.redirect') }}">
                            <div id="googleSignIn">
                                <i class="fab fa-google"></i>
                                <span>{{ __('Continue with Google') }}</span>
                            </div>
                        </a>
                    </div>

                    <div class="signup-link">
                        {{ __('Don\'t have an account?') }} <a
                            href="{{ route('register', app()->getLocale()) }}">{{ __('Sign up') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
