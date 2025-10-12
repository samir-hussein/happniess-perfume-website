<!-- Header & Navigation -->
<header class="fixed-header">
    <div class="container">
        <nav class="navbar">
            <div class="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>

            <a href="{{ route('home', app()->getLocale()) }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" width="130">
            </a>

            <div class="nav-links">
                <a href="{{ route('home', app()->getLocale()) }}">{{ __('Home') }}</a>

                <div class="dropdown">
                    <div class="dropdown-btn">
                        <a href="#">{{ __('Categories') }} <i class="fas fa-chevron-down"></i></a>
                    </div>
                    <div class="dropdown-content">
                        @foreach ($categories as $category)
                            <a
                                href="{{ route('products', app()->getLocale()) }}?categories={{ $category->id }}">{{ $category->{'name_' . app()->getLocale()} }}</a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('products', app()->getLocale()) }}">{{ __('Products') }}</a>

                <a href="{{ route('order.index', app()->getLocale()) }}">{{ __('My Orders') }}</a>

                <a class="for-mobile-show"
                    href="{{ route('favorite', app()->getLocale()) }}">{{ __('Favorites') }}</a>
                @guest
                    <a class="for-mobile-show" href="{{ route('login', app()->getLocale()) }}">{{ __('Sign In') }}</a>
                @endguest
                @auth
                    <a class="for-mobile-show" href="{{ route('auth.logout') }}">{{ __('Sign Out') }}</a>
                @endauth

                <div>
                    @php
                        $segments = request()->segments();
                        $segments[0] = request()->segment(1) === 'ar' ? 'en' : 'ar';
                        $url =
                            url(implode('/', $segments)) .
                            (request()->getQueryString() ? '?' . request()->getQueryString() : '');
                    @endphp
                    <a href="{{ $url }}">
                        <i class="fas fa-globe"></i>
                        @if (app()->getLocale() === 'en')
                            {{ config('app.locales.ar') }}
                        @else
                            {{ config('app.locales.en') }}
                        @endif
                    </a>
                </div>
            </div>

            <div class="nav-icons">
                <div style="position: relative;" id="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count" style="display: none;">0</span>
                </div>

                <div style="position: relative;" class="for-mobile-hide">
                    <a href="{{ route('favorite', app()->getLocale()) }}">
                        <i class="fas fa-heart"></i>
                        <span class="favorite-count" style="display: none;">0</span>
                    </a>
                </div>

                @guest
                    <div class="for-mobile-hide">
                        <a href="{{ route('login', app()->getLocale()) }}">
                            <i class="fas fa-user"></i>
                        </a>
                    </div>
                @endguest
                @auth
                    <div class="for-mobile-hide">
                        <a href="{{ route('auth.logout') }}">
                            <i
                                class="fas fa-{{ app()->getLocale() === 'ar' ? 'sign-out-alt fa-flip-horizontal' : 'sign-out-alt' }}"></i>
                        </a>
                    </div>
                @endauth
            </div>
        </nav>
    </div>
</header>
