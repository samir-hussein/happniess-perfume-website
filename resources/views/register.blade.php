@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
    <!-- Sign Up Section -->
    <section class="signup-section">
        <div class="container">
            <div class="signup-container">
                <div class="signup-image">
                    <h2>{{ __('Join Our Fragrance Community') }}</h2>
                    <p>{{ __('Create an account to explore our exclusive collection, receive personalized recommendations, and enjoy member-only benefits.') }}
                    </p>
                </div>
                <div class="signup-form">
                    <h2>{{ __('Create Account') }}</h2>
                    <p>{{ __('Welcome to Happiness Perfume. Please fill in your details.') }}</p>

                    <form id="signupForm" action="{{ route('auth.register') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="fullName">{{ __('Full Name') }}</label>
                            <input type="text" id="fullName" name="name" class="form-control"
                                placeholder="{{ __('Enter your full name') }}" required value="{{ old('name') }}">
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="email">{{ __('Email Address') }}</label>
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="{{ __('Enter your email') }}" required value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <div class="form-group password-field">
                            <label for="password">{{ __('Password') }}</label>
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="{{ __('Create a password') }}" required>
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>

                        <div class="form-group password-field">
                            <label for="confirmPassword">{{ __('Confirm Password') }}</label>
                            <input type="password" id="confirmPassword" name="password_confirmation" class="form-control"
                                placeholder="{{ __('Confirm your password') }}" required>
                            @if ($errors->has('password_confirmation'))
                                <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="phone">{{ __('Phone Number') }}</label>
                            <input type="tel" id="phone" name="phone" class="form-control"
                                placeholder="{{ __('Enter your phone number') }}" value="{{ old('phone') }}">
                            @if ($errors->has('phone'))
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>

                        <button type="submit" class="btn">{{ __('Create Account') }}</button>
                    </form>

                    <div class="divider">{{ __('or continue with') }}</div>

                    <div class="social-login">
                        <a href="{{ route('auth.google.redirect') }}" class="social-btn google-btn">
                            <div id="googleSignIn">
                                <i class="fab fa-google"></i>
                                <span>{{ __('Continue with Google') }}</span>
                            </div>
                        </a>
                    </div>

                    <div class="login-link">
                        {{ __('Already have an account?') }} <a
                            href="{{ route('login', app()->getLocale()) }}">{{ __('Sign In') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
