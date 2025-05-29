<!-- Header & Navigation -->
<header>
    <div class="container">
        <nav class="navbar">
            <div class="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>

            <a href="{{ route('home', app()->getLocale()) }}" class="logo">Happniess<span>Perfume</span></a>

            <div class="nav-links">
                <a href="{{ route('home', app()->getLocale()) }}">{{ __('Home') }}</a>

                <div class="dropdown">
                    <div class="dropdown-btn">
                        <a href="#">{{ __('Categories') }} <i class="fas fa-chevron-down"></i></a>
                    </div>
                    <div class="dropdown-content">
                        @foreach ($categories as $category)
                            <a
                                href="{{ route('home', app()->getLocale()) }}?categories={{ $category->id }}">{{ $category->{'name_' . app()->getLocale()} }}</a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('order.index', app()->getLocale()) }}">{{ __('My Orders') }}</a>

                <div>
                    <a
                        href="{{ url((request()->segment(1) === 'en' ? 'ar' : 'en') . '/' . request()->segment(2) . '/' . request()->segment(3)) }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}">
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
                <div style="position: relative;">
                    <a href="{{ route('favorite', app()->getLocale()) }}">
                        <i class="fas fa-heart"></i>
                        <span class="favorite-count" style="display: none;">0</span>
                    </a>
                </div>
                <div style="position: relative;" id="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count" style="display: none;">0</span>
                </div>

                @guest
                    <div>
                        <a href="{{ route('login', app()->getLocale()) }}">
                            <i class="fas fa-user"></i>
                        </a>
                    </div>
                @endguest
                @auth
                    <div>
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
