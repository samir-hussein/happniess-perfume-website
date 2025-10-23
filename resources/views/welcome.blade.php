@extends('layouts.app')

@section('title', 'Happiness Perfume | عطور السعادة')

@section('styles')
    <style>
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url("{{ $heroSetting->image }}");
            background-size: {{ $heroSetting->image_size }};
            background-color: {{ $heroSetting->background_color }};
            background-repeat: no-repeat;
	        background-position: center;
        }
    </style>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Happiness Perfume</h1>
                <h2>{{ __('Let every fragrance tell your story.') }}</h2>
                <a href="{{ route('products', app()->getLocale()) }}" class="btn shop-btn">{{ __('Shop Now') }}</a>
            </div>
        </div>
    </section>

    <!-- Search Bar -->
    <div class="container">
        <div class="search-container">
            <input type="text" placeholder="{{ __('Search for your perfect fragrance...') }}" id="searchInput"
                value="{{ request('search') }}">
            <i class="fas fa-search" id="searchIcon"></i>
        </div>
    </div>

    <!-- Categories Section -->
    @if ($categories->isNotEmpty())
        <section class="categories-section">
            <div class="container">
                <div class="section-title">
                    <h2>{{ __('Shop by Category') }}</h2>
                    <p>{{ __('Discover your perfect scent') }}</p>
                </div>
                <div class="categories-grid">
                    @foreach ($categories as $category)
                        @php
                            $randomProduct = $category->products->random();
                            $backgroundImage = $randomProduct ? $randomProduct->main_image : '';
                        @endphp
                        <a href="{{ route('products', [app()->getLocale(), 'categories' => $category->id]) }}" 
                           class="category-card" 
                           style="animation-delay: {{ $loop->index * 0.15 }}s; background-image: url('{{ $backgroundImage }}');">
                            <div class="category-overlay"></div>
                            <div class="category-shine"></div>
                            <div class="category-info">
                                <div class="category-icon-wrapper">
                                    <i class="fas fa-gem"></i>
                                </div>
                                <h3 class="category-name">{{ $category->{'name_' . app()->getLocale()} }}</h3>
                                <span class="category-products">{{ $category->products->count() }} {{ __('Products') }}</span>
                                <div class="category-explore">
                                    <span>{{ __('Explore') }}</span>
                                    <i class="fas fa-long-arrow-alt-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- New Arrivals Section -->
    @if ($newProducts->isNotEmpty())
        <section class="new-arrivals-section">
            <div class="container">
                <div class="section-header">
                    <div class="section-title">
                        <h2>{{ __('New Arrivals') }}</h2>
                        <p>{{ __('Discover our latest fragrances') }}</p>
                    </div>
                    <a href="{{ route('products', [app()->getLocale(), 'tags' => 'new']) }}" class="view-all-btn">
                        {{ __('View All') }} <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i>
                    </a>
                </div>
                <div class="new-arrivals-grid">
                    @foreach ($newProducts as $product)
                        <div class="new-product-card" data-id="{{ $product->id }}">
                            <a href="{{ route('product', [app()->getLocale(), $product->id, $product->sizes->first()->size]) }}" 
                               class="new-product-link">
                                <div class="new-product-image">
                                    <img src="{{ $product->main_image }}" alt="{{ $product->{'name_' . app()->getLocale()} }}" loading="lazy">
                                    <div class="new-badge">{{ __('New') }}</div>
                                </div>
                                <div class="new-product-info">
                                    <h3 class="new-product-name">{{ $product->{'name_' . app()->getLocale()} }}</h3>
                                    <p class="new-product-category">{{ $product->category->{'name_' . app()->getLocale()} }}</p>
                                    <div class="new-product-price">
                                        @if ($product->discount_amount > 0)
                                            <span class="price-original">{{ $product->sizes->first()->price }} {{ __('EGP') }}</span>
                                            <span class="price-discounted">{{ $product->priceAfterDiscount }} {{ __('EGP') }}</span>
                                        @else
                                            <span class="price">{{ $product->sizes->first()->price }} {{ __('EGP') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Best Sellers Section -->
    @if ($bestSellerProducts->isNotEmpty())
        <section class="best-sellers-section">
            <div class="container">
                <div class="section-header">
                    <div class="section-title">
                        <h2>{{ __('Best Sellers') }}</h2>
                        <p>{{ __('Our most popular fragrances') }}</p>
                    </div>
                    <a href="{{ route('products', [app()->getLocale(), 'tags' => 'best seller']) }}" class="view-all-btn">
                        {{ __('View All') }} <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i>
                    </a>
                </div>
                <div class="best-sellers-grid">
                    @foreach ($bestSellerProducts as $product)
                        <div class="best-seller-card" data-id="{{ $product->id }}">
                            <a href="{{ route('product', [app()->getLocale(), $product->id, $product->sizes->first()->size]) }}" 
                               class="best-seller-link">
                                <div class="best-seller-image">
                                    <img src="{{ $product->main_image }}" alt="{{ $product->{'name_' . app()->getLocale()} }}" loading="lazy">
                                    <div class="best-seller-badge">{{ __('Best Seller') }}</div>
                                </div>
                                <div class="best-seller-info">
                                    <h3 class="best-seller-name">{{ $product->{'name_' . app()->getLocale()} }}</h3>
                                    <p class="best-seller-category">{{ $product->category->{'name_' . app()->getLocale()} }}</p>
                                    <div class="best-seller-price">
                                        @if ($product->discount_amount > 0)
                                            <span class="price-original">{{ $product->sizes->first()->price }} {{ __('EGP') }}</span>
                                            <span class="price-discounted">{{ $product->priceAfterDiscount }} {{ __('EGP') }}</span>
                                        @else
                                            <span class="price">{{ $product->sizes->first()->price }} {{ __('EGP') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Products Section -->
    <section class="products">
        <div class="container">
            <div class="section-title">
                <h2>{{ __('Our Collection') }}</h2>
                <p>{{ __('Explore our exquisite range of fragrances') }}</p>
            </div>
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

            <!-- <div class="view-all">
                <a href="{{ route('products', app()->getLocale()) }}">{{ __('View All') }} <i
                        class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i></a>
            </div> -->

             <!-- Pagination -->
        @if ($products->hasPages())
            <div class="pagination">
                <ul class="pagination-list">
                    <li class="pagination-item">
                        <a href="{{ $products->appends(request()->except('page'))->previousPageUrl() }}" id="prevPage"
                            class="pagination-link"><i
                                class="fas {{ app()->getLocale() === 'ar' ? 'fa-chevron-right' : 'fa-chevron-left' }}"></i></a>
                    </li>
                    @php
                        $currentPage = $products->currentPage();
                        $lastPage = $products->lastPage();
                    @endphp

                    <li class="pagination-item {{ $currentPage == 1 ? 'active' : '' }}">
                        {{-- Always show page 1 --}}
                        <a href="{{ $products->appends(request()->except('page'))->url(1) }}">1</a>
                    </li>

                    @if ($currentPage > 2 && $currentPage <= $lastPage && $lastPage > 3)
                        <li class="pagination-item">
                            {{-- Show ellipsis if currentPage > 2 --}}
                            <span class="pagination-ellipsis">...</span>
                        </li>
                    @endif

                    {{-- Show middle pages if not near the start or end --}}
                    @for ($i = max(2, $currentPage); $i <= min($lastPage - 1, $currentPage + 1); $i++)
                        <li class="pagination-item {{ $currentPage == $i ? 'active' : '' }}">
                            <a
                                href="{{ $products->appends(request()->except('page'))->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    {{-- Show ellipsis if not near the end --}}
                    @if ($currentPage < $lastPage - 2)
                        <li class="pagination-item">
                            <span class="pagination-ellipsis">...</span>
                        </li>
                    @endif

                    @if ($currentPage == $lastPage && $lastPage > 3)
                        <li class="pagination-item {{ $currentPage == $lastPage - 1 ? 'active' : '' }}">
                            <a
                                href="{{ $products->appends(request()->except('page'))->url($lastPage - 1) }}">{{ $lastPage - 1 }}</a>
                        </li>
                    @endif

                    {{-- Always show last page if it's not 1 --}}
                    @if ($lastPage > 1)
                        <li class="pagination-item {{ $currentPage == $lastPage ? 'active' : '' }}">
                            <a
                                href="{{ $products->appends(request()->except('page'))->url($lastPage) }}">{{ $lastPage }}</a>
                        </li>
                    @endif

                    <li class="pagination-item">
                        <a href="{{ $products->appends(request()->except('page'))->nextPageUrl() }}" id="nextPage"
                            class="pagination-link"><i
                                class="fas {{ app()->getLocale() === 'ar' ? 'fa-chevron-left' : 'fa-chevron-right' }}"></i></a>
                    </li>
                </ul>
            </div>
        @endif
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        const products = Array.from(document.querySelectorAll('.product-card'));
        const searchInput = document.getElementById('searchInput');
        const searchIcon = document.getElementById('searchIcon');

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

        searchIcon.addEventListener('click', function() {
            const searchValue = searchInput.value.trim();

            const url = new URL("{{ route('products', app()->getLocale()) }}");
            url.searchParams.set('search', searchValue);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        });
    </script>
@endsection
