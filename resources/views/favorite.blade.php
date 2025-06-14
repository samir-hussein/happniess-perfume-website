@extends('layouts.app')

@section('styles')
    <style>
        .favorites-empty {
            text-align: center;
            padding: 60px 0;
        }

        .favorites-empty i {
            font-size: 60px;
            color: var(--royal-gold);
            margin-bottom: 20px;
        }

        .favorites-empty h3 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .favorites-empty p {
            margin-bottom: 25px;
            color: var(--deep-bronze);
        }

        .favorites-header {
            text-align: center;
            margin-bottom: 40px;
        }
    </style>
@endsection

@section('content')
    <!-- Products Section -->
    <section class="products">
        <div class="container">
            @if (!$products->isEmpty())
                <div class="section-title">
                    <h2>My Favorite Items</h2>
                </div>
            @endif
            @if ($products->isEmpty())
                <div class="favorites-header">
                    <div class="favorites-empty" id="favorites-empty">
                        <i class="far fa-heart"></i>
                        <h3>{{ __('Your favorites list is empty') }}</h3>
                        <p>{{ __('You haven\'t added any items to your favorites yet.') }}</p>
                        <a href="{{ route('products', app()->getLocale()) }}" class="btn">{{ __('Browse Products') }}</a>
                    </div>
                </div>
            @else
                <div class="product-grid" id="productGrid">
                    @foreach ($products as $product)
                        <div class="product-card" data-id="{{ $product->id }}"
                            data-category="{{ $product->category->name }}" data-price="{{ $product->price }}">
                            @if ($product->tag_ar)
                                <div class="product-badge">{{ $product->{'tag_' . app()->getLocale()} }}</div>
                            @endif
                            <div class="product-img">
                                <a
                                    href="{{ route('product', [app()->getLocale(), $product->id, 'size' => $product->sizes->first()->size]) }}">
                                    <img src="{{ $product->main_image }}"
                                        alt="{{ $product->name_en . ' - Happiness Perfume' }}" loading="lazy">
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
                                    <button class="add-to-cart" data-id="{{ $product->id }}"
                                        data-size="{{ $product->sizes->first()->size }}" onclick="addToCart(this)"><i
                                            class="fas fa-cart-plus"></i></button>
                                    <button class="add-to-fav {{ in_array($product->id, $favorites) ? 'favorited' : '' }}"
                                        data-id="{{ $product->id }}" data-size="{{ $product->sizes->first()->size }}"><i
                                            class="{{ in_array($product->id, $favorites) ? 'fas' : 'far' }} fa-heart"></i></button>
                                </div>
                            </div>
                        </div>
                    @endforeach
            @endif
        </div>

        <!-- Pagination -->
        @if (!$products->isEmpty() && $products->hasPages())
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
                            <a href="{{ $products->appends(request()->except('page'))->url($i) }}">{{ $i }}</a>
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
