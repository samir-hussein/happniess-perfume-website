@extends('layouts.app')

@section('title', 'Happiness Perfume | عطور السعادة')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>{{ __('Discover Your Signature Scent With Happiness Perfume') }}</h1>
                <p>{{ __('Experience luxury fragrances crafted with the finest ingredients to elevate your everyday moments.') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Search Bar -->
    <div class="container">
        <div class="search-container">
            <input type="text" placeholder="{{ __('Search for your perfect fragrance...') }}" id="searchInput"
                value="{{ request('search') }}">
            <i class="fas fa-search"></i>
        </div>
    </div>

    <!-- Products Section -->
    <section class="products">
        <div class="container">
            <div class="product-grid" id="productGrid">
                @if ($products->isEmpty())
                    <p class="no-products">{{ __('No products found') }}</p>
                @else
                    @foreach ($products as $product)
                        <div class="product-card" data-id="{{ $product->id }}"
                            data-category="{{ $product->category->name }}" data-price="{{ $product->price }}">
                            @if ($product->sizes->first()->quantity == 0)
                                <div class="product-badge">{{ __('Out of Stock') }}</div>
                            @else
                                @if ($product->tag_ar)
                                    <div class="product-badge">{{ $product->{'tag_' . app()->getLocale()} }}</div>
                                @endif
                            @endif

                            <div class="product-img">
                                <a
                                    href="{{ route('product', [app()->getLocale(), $product->id, 'size' => $product->sizes->first()->size]) }}">
                                    <img src="{{ $product->main_image }}"
                                        alt="{{ $product->name_en . ' - Happiness Perfume' }}" loading="lazy"
                                        width="100" height="300">
                                </a>
                            </div>
                            <div class="product-info">
                                <a
                                    href="{{ route('product', [app()->getLocale(), $product->id, 'size' => $product->sizes->first()->size]) }}">
                                    <h3 dir="auto">{{ $product->{'name_' . app()->getLocale()} }} -
                                        <span class="size">{{ $product->sizes->first()->size }}
                                            {{ __('ml') }}</span>
                                    </h3>
                                </a>
                                @if ($product->discount_amount > 0)
                                    <div class="product-price">
                                        <span class="discounted-price">{{ $product->sizes->first()->price }}
                                            {{ __('EGP') }}</span>
                                        <span>{{ $product->priceAfterDiscount }}
                                            {{ __('EGP') }}</span>
                                    </div>
                                @else
                                    <div class="product-price">{{ $product->sizes->first()->price }} {{ __('EGP') }}
                                    </div>
                                @endif
                                <div class="product-actions">
                                    @if ($product->sizes->first()->quantity > 0)
                                        <button class="add-to-cart" data-id="{{ $product->id }}"
                                            data-size="{{ $product->sizes->first()->size }}" onclick="addToCart(this)"><i
                                                class="fas fa-cart-plus"></i></button>
                                    @endif
                                    <button class="add-to-fav {{ in_array($product->id, $favorites) ? 'favorited' : '' }}"
                                        data-id="{{ $product->id }}" data-size="{{ $product->sizes->first()->size }}"><i
                                            class="{{ in_array($product->id, $favorites) ? 'fas' : 'far' }} fa-heart"></i></button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="view-all">
                <a href="{{ route('products', app()->getLocale()) }}">{{ __('View All') }} <i
                        class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i></a>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        const products = Array.from(document.querySelectorAll('.product-card'));
        const searchInput = document.getElementById('searchInput');

        // Search functionality
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const searchValue = this.value.trim();

                const url = new URL("{{ route('products', app()->getLocale()) }}");
                url.searchParams.set('search', searchValue);
                url.searchParams.set('page', 1);
                window.location.href = url.toString();
            }
        });
    </script>
@endsection
