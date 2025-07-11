@extends('layouts.app')

@section('title', 'Happiness Perfume | عطور السعادة')

@section('content')
    <!-- Search Bar -->
    <div class="container">
        <div class="search-container">
            <input type="text" placeholder="{{ __('Search for your perfect fragrance...') }}" id="searchInput"
                value="{{ request('search') }}">
            <i class="fas fa-search"></i>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="container">
        <button class="mobile-filter-toggle" id="mobileFilterToggle">
            <i class="fas fa-filter"></i>
            {{ __('Filter Products') }}
        </button>
    </div>

    <!-- Enhanced Filter Section -->
    <div class="container">
        <div class="filter-section" id="filterSection">
            <div class="filter-header">
                <h3 class="filter-title"><i class="fas fa-sliders-h"></i> {{ __('Refine Your Selection') }}</h3>
                <a href="{{ route('products', app()->getLocale()) }}" class="reset-filters">
                    <i class="fas fa-undo"></i>
                    <span>{{ __('Reset All') }}</span>
                </a>
            </div>

            <div class="filter-options">
                <div class="filter-group">
                    <label class="filter-label">{{ __('Categories') }}</label>
                    <div class="filter-dropdown">
                        <button class="filter-dropdown-btn">
                            <span>{{ __('Select Categories') }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="filter-dropdown-content">
                            @if ($categories->isNotEmpty())
                                @foreach ($categories as $category)
                                    <div class="filter-option">
                                        <input type="checkbox" id="category-{{ $category->id }}"
                                            {{ in_array($category->id, explode(',', request('categories', ''))) ? 'checked' : '' }}>
                                        <label
                                            for="category-{{ $category->id }}">{{ $category->{'name_' . app()->getLocale()} }}</label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label">{{ __('Tags') }}</label>
                    <div class="filter-dropdown">
                        <button class="filter-dropdown-btn">
                            <span>{{ __('Select Tags') }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="filter-dropdown-content">
                            @if (count($tags) > 0)
                                @foreach ($tags as $tag)
                                    <div class="filter-option">
                                        <input type="checkbox" id="tag-{{ $tag }}"
                                            {{ in_array($tag, explode(',', request('tags', ''))) ? 'checked' : '' }}>
                                        <label for="tag-{{ $tag }}">{{ $tag }}</label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label">{{ __('Price Range') }}</label>
                    <div class="price-filter">
                        <div class="price-range-container">
                            <input type="range" min="{{ $minPrice }}" max="{{ $maxPrice }}"
                                value="{{ request('price', $maxPrice) }}" class="price-range" id="priceRange">
                            <div class="price-values">
                                <span>{{ $minPrice }} {{ __('EGP') }}</span>
                                <span id="currentPrice">{{ request('price', $maxPrice) }} {{ __('EGP') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label">{{ __('Sort By') }}</label>
                    <div class="filter-dropdown">
                        <button class="filter-dropdown-btn">
                            <span id="sortOption"
                                data-sort="{{ request('sort', 'asc') }}">{{ request('sort', 'asc') === 'asc' ? __('Price: Low to High') : __('Price: High to Low') }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="filter-dropdown-content">
                            <div class="filter-option" data-sort="asc">
                                {{ __('Price: Low to High') }}
                            </div>
                            <div class="filter-option" data-sort="desc">
                                {{ __('Price: High to Low') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label">{{ __('Size') }}</label>
                    <div class="filter-dropdown">
                        <button class="filter-dropdown-btn">
                            <span id="sizeOption"
                                data-size="{{ request('size', 'any') }}">{{ request('size') ? request('size') . ' ' . __('ml') : __('Any') }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="filter-dropdown-content">
                            @foreach ($sizes as $size)
                                <div class="filter-option" data-size="{{ $size }}">
                                    {{ $size }} {{ __('ml') }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="apply-filters">
                <button class="apply-btn" id="applyFiltersBtn">
                    <i class="fas fa-check"></i>
                    {{ __('Apply Filters') }}
                </button>
            </div>
        </div>
    </div>
    <!-- Products Section -->
    <section class="products">
        <div class="container">
            @if ($products->isEmpty())
                <p class="no-products">{{ __('No products found') }}</p>
            @else
                <div class="product-grid" id="productGrid">
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
                                        data-id="{{ $product->id }}"
                                        data-size="{{ $product->sizes->first()->size }}"><i
                                            class="{{ in_array($product->id, $favorites) ? 'fas' : 'far' }} fa-heart"></i></button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

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
        // Filter functionality
        const filterDropdownBtns = document.querySelectorAll('.filter-dropdown-btn');
        const priceRange = document.getElementById('priceRange');
        const currentPrice = document.getElementById('currentPrice');
        const products = Array.from(document.querySelectorAll('.product-card'));
        const mobileFilterToggle = document.getElementById('mobileFilterToggle');
        const filterSection = document.getElementById('filterSection');
        const searchInput = document.getElementById('searchInput');
        const applyFiltersBtn = document.getElementById('applyFiltersBtn');

        // Mobile filter toggle
        mobileFilterToggle.addEventListener('click', function() {
            filterSection.classList.toggle('active');
            this.innerHTML = filterSection.classList.contains('active') ?
                '<i class="fas fa-times"></i> {{ __('Close Filters') }}' :
                '<i class="fas fa-filter"></i> {{ __('Filter Products') }}';
        });

        // Toggle dropdowns
        filterDropdownBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const dropdown = this.nextElementSibling;
                this.classList.toggle('active');
                dropdown.classList.toggle('active');

                // Close other open dropdowns
                filterDropdownBtns.forEach(otherBtn => {
                    if (otherBtn !== this && otherBtn.classList.contains('active')) {
                        otherBtn.classList.remove('active');
                        otherBtn.nextElementSibling.classList.remove('active');
                    }
                });
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.filter-dropdown')) {
                filterDropdownBtns.forEach(btn => {
                    btn.classList.remove('active');
                    btn.nextElementSibling.classList.remove('active');
                });
            }
        });

        // Price range display
        priceRange.addEventListener('input', function() {
            if (this.value == "{{ $maxPrice }}") {
                currentPrice.textContent = '{{ $maxPrice }} {{ __('EGP') }}+';
            } else {
                currentPrice.textContent = this.value + ' {{ __('EGP') }}';
            }
        });

        // Sort option selection
        document.querySelectorAll('.filter-option[data-sort]').forEach(option => {
            option.addEventListener('click', function() {
                const dropdownBtn = this.closest('.filter-dropdown').querySelector('.filter-dropdown-btn');
                dropdownBtn.innerHTML =
                    `<span id="sortOption" data-sort="${this.dataset.sort}">${this.textContent}</span><i class="fas fa-chevron-down"></i>`;
            });
        });

        // Size selection
        document.querySelectorAll('.filter-option[data-size]').forEach(option => {
            option.addEventListener('click', function() {
                const dropdownBtn = this.closest('.filter-dropdown').querySelector('.filter-dropdown-btn');
                dropdownBtn.innerHTML =
                    `<span id="sizeOption" data-size="${this.dataset.size}">${this.textContent}</span><i class="fas fa-chevron-down"></i>`;
            });
        });

        // Search functionality
        searchInput.addEventListener('keydown', function(e) {
            // Only trigger search on Enter key press
            if (e.key === 'Enter') {
                const searchValue = this.value.trim();

                const url = new URL(window.location.href);
                url.searchParams.set('search', searchValue);
                url.searchParams.set('page', 1); // reset to first page
                window.location.href = url.toString();
            }
        });

        applyFiltersBtn.addEventListener('click', function() {
            // Get selected categories
            const selectedCategories = [];
            document.querySelectorAll('input[id^="category-"]:checked').forEach(checkbox => {
                selectedCategories.push(checkbox.id.replace('category-', ''));
            });

            // Get selected tags
            const selectedTags = [];
            document.querySelectorAll('input[id^="tag-"]:checked').forEach(checkbox => {
                selectedTags.push(checkbox.id.replace('tag-', ''));
            });

            // Get price range
            const priceValue = document.getElementById('priceRange').value;

            // Get sort option
            let sortValue = 'asc'; // Default value
            const sortBtn = document.getElementById('sortOption');
            if (sortBtn.dataset.sort === 'desc') {
                sortValue = 'desc';
            }

            // Get size option
            let sizeValue = 'any'; // Default value
            const sizeBtn = document.getElementById('sizeOption');
            if (sizeBtn.dataset.size) {
                sizeValue = sizeBtn.dataset.size;
            }

            // Build URL with all parameters
            const url = new URL(window.location.href);

            // Add categories as a comma-separated list
            if (selectedCategories.length > 0) {
                url.searchParams.set('categories', selectedCategories.join(','));
            } else {
                url.searchParams.delete('categories');
            }

            // Add tags as a comma-separated list
            if (selectedTags.length > 0) {
                url.searchParams.set('tags', selectedTags.join(','));
            } else {
                url.searchParams.delete('tags');
            }

            // Add price and sort
            url.searchParams.set('price', priceValue);
            url.searchParams.set('sort', sortValue);

            // Add size
            if (sizeValue !== 'any') {
                url.searchParams.set('size', sizeValue);
            }

            // Reset to first page
            url.searchParams.set('page', 1);

            // Navigate to the filtered URL
            window.location.href = url.toString();
        });
    </script>
@endsection
