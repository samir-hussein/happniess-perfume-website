@extends('layouts.app')

@if (app()->getLocale() === 'ar')
    @section('title', $product->name_ar . ' - عطور السعادة')
@else
    @section('title', $product->name_en . ' - Happiness Perfume')
@endif

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}?v={{ filemtime(public_path('css/product.css')) }}">

    @if (app()->getLocale() === 'ar')
        <link rel="stylesheet" href="{{ asset('css/product-ar.css') }}?v={{ filemtime(public_path('css/product-ar.css')) }}">
    @endif
@endsection

@section('content')
    <!-- Product Main Section -->
    <div class="product-page">

        <div class="container">
            <div class="product-container">
                <div class="product-gallery">
                    @if ($product->sizes->where('size', request()->size)->first()->quantity == 0)
                        <div class="product-badge">{{ __('Out of Stock') }}</div>
                    @else
                        @if ($product->tag_ar)
                            <div class="product-badge">{{ $product->{'tag_' . app()->getLocale()} }}</div>
                        @endif
                    @endif
                    <img src="{{ $product->main_image }}" alt="{{ $product->name_en . ' - Happiness Perfume' }}"
                        class="main-image" id="mainImage" loading="lazy">

                    <div class="thumbnail-container">
                        @foreach ($product->allImages as $image)
                            @if ($loop->first)
                                <img src="{{ $image }}" alt="{{ $product->name_en . ' - Happiness Perfume' }}"
                                    class="thumbnail active" data-image="{{ $image }}" loading="lazy">
                            @else
                                @if ($image)
                                    <img src="{{ $image }}" alt="{{ $product->name_en . ' - Happiness Perfume' }}"
                                        class="thumbnail" data-image="{{ $image }}" loading="lazy">
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="product-details">
                    <h1 class="product-title">{{ $product->{'name_' . app()->getLocale()} }}</h1>
                    <span class="product-category">{{ $product->category->{'name_' . app()->getLocale()} }}</span>

                    <div>
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $product->reviews_avg_rate)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                        ({{ $product->reviews_count }})
                    </div>

                    <div class="product-price">
                        @if ($product->discount_amount > 0)
                            <span class="current-price">{{ $product->size_price_after_discount }}
                                {{ __('EGP') }}</span>
                            <span class="original-price">{{ $product->size_price }}
                                {{ __('EGP') }}</span>
                        @else
                            <span class="current-price">{{ $product->size_price }}
                                {{ __('EGP') }}</span>
                        @endif
                    </div>

                    <div class="size-options">
                        <h3 class="size-title">{{ __('Select Size') }}:</h3>
                        <div class="size-buttons">
                            @foreach ($product->sizes as $size)
                                <a href="{{ route('product', ['locale' => app()->getLocale(), 'id' => $product->id, 'size' => $size->size]) }}"
                                    class="size-btn {{ $size->size == (int) request()->size ? 'active' : '' }}"
                                    data-size="{{ $size->size }}" data-price="{{ $size->price }}">{{ $size->size }}
                                    {{ __('ml') }}</a>
                            @endforeach
                        </div>
                    </div>

                    <div class="product-actions single-product-actions" id="originalActions">
                        <button class="add-to-fav {{ in_array($product->id, $favorites) ? 'favorited' : '' }}"
                            data-id="{{ $product->id }}" data-size="{{ request()->size }}"><i
                                class="{{ in_array($product->id, $favorites) ? 'fas' : 'far' }} fa-heart"></i></button>
                        @if ($product->sizes->where('size', request()->size)->first()->quantity > 0)
                            <form
                                action="{{ route('buy.now', ['locale' => app()->getLocale(), 'product_id' => $product->id, 'size' => request()->size]) }}"
                                method="POST">
                                @csrf
                                <button type="submit" class="buy-now">{{ __('Buy Now') }}</button>
                            </form>
                            <button class="add-to-cart" data-id="{{ $product->id }}" data-size="{{ request()->size }}"
                                onclick="addToCart(this)"><i class="fas fa-cart-plus"></i></button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Fixed Bottom Action Bar -->
            <div class="fixed-action-bar" id="fixedActionBar">
                <div class="fixed-action-content">
                    <div class="fixed-product-info">
                        <img src="{{ $product->main_image }}" alt="{{ $product->name_en }}" class="fixed-product-image">
                        <div class="fixed-product-details">
                            <h4 class="fixed-product-name">{{ $product->{'name_' . app()->getLocale()} }}</h4>
                            <div class="fixed-product-price">
                                @if ($product->discount_amount > 0)
                                    <span class="fixed-current-price">{{ $product->size_price_after_discount }} {{ __('EGP') }}</span>
                                    <span class="fixed-original-price">{{ $product->size_price }} {{ __('EGP') }}</span>
                                @else
                                    <span class="fixed-current-price">{{ $product->size_price }} {{ __('EGP') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="fixed-actions">
                        @if ($product->sizes->where('size', request()->size)->first()->quantity > 0)
                            <form
                                action="{{ route('buy.now', ['locale' => app()->getLocale(), 'product_id' => $product->id, 'size' => request()->size]) }}"
                                method="POST">
                                @csrf
                                <button type="submit" class="buy-now">{{ __('Buy Now') }}</button>
                            </form>
                            <button class="add-to-cart" data-id="{{ $product->id }}" data-size="{{ request()->size }}"
                                onclick="addToCart(this)"><i class="fas fa-cart-plus"></i></button>
                        @endif
                    </div>
                </div>
            </div>

            <p class="product-description">
                {!! $product->{'description_' . app()->getLocale()} !!}
            </p>

            @auth
                <!-- Review Form -->
                <div class="review-form-container">
                    <h3 class="review-form-title">
                        {{ count($product->reviews) == 0 ? __('Add Your Review') : __('Update Your Review') }}
                    </h3>
                    <form id="reviewForm" class="review-form"
                        action="{{ route('product.review.create', [app()->getLocale(), $product->id]) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">{{ __('Your Rating') }}</label>
                            <div class="rating-stars">
                                <input type="radio" id="star1" name="rate" value="1">
                                <label for="star1" class="star-label"><i class="fas fa-star"></i></label>

                                <input type="radio" id="star2" name="rate" value="2">
                                <label for="star2" class="star-label"><i class="fas fa-star"></i></label>

                                <input type="radio" id="star3" name="rate" value="3">
                                <label for="star3" class="star-label"><i class="fas fa-star"></i></label>

                                <input type="radio" id="star4" name="rate" value="4">
                                <label for="star4" class="star-label"><i class="fas fa-star"></i></label>

                                <input type="radio" id="star5" name="rate" value="5">
                                <label for="star5" class="star-label"><i class="fas fa-star"></i></label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="reviewText" class="form-label">{{ __('Your Review') }}</label>
                            <textarea id="reviewText" class="form-textarea" rows="5" required name="comment" dir="auto">{{ count($product->reviews) == 0 ? '' : $product->reviews->first()->comment }}</textarea>
                        </div>

                        <button type="submit"
                            class="submit-review-btn">{{ count($product->reviews) == 0 ? __('Submit Review') : __('Update Review') }}</button>
                    </form>

                    @if (count($product->reviews) > 0)
                        <form id="deleteReviewForm"
                            action="{{ route('product.review.delete', [app()->getLocale(), $product->id]) }}" method="POST">
                            @csrf
                            @method('Delete')
                            <button type="submit" for="deleteReviewForm"
                                class="delete-review-btn">{{ __('Delete Review') }}</button>
                        </form>
                    @endif
                </div>
            @endauth

            <!-- Related Products -->
            @if ($relatedProducts->isNotEmpty())
                <section class="related-products">
                    <div class="section-title">
                        <h2>{{ __('Related Products') }}</h2>
                    </div>

                    <div class="product-grid" id="productGrid">
                        @foreach ($relatedProducts as $relatedProduct)
                            <div class="product-card" data-id="{{ $relatedProduct->id }}"
                                data-category="{{ $relatedProduct->category->name }}"
                                data-price="{{ $relatedProduct->price }}">
                                @if ($relatedProduct->sizes->first()->quantity == 0)
                                    <div class="product-badge">{{ __('Out of Stock') }}</div>
                                @else
                                    @if ($relatedProduct->tag_ar)
                                        <div class="product-badge">{{ $relatedProduct->{'tag_' . app()->getLocale()} }}
                                        </div>
                                    @endif
                                @endif
                                <div class="product-img">
                                    <a
                                        href="{{ route('product', [app()->getLocale(), $relatedProduct->id, 'size' => $relatedProduct->sizes->first()->size]) }}">
                                        <img src="{{ $relatedProduct->main_image }}"
                                            alt="{{ $relatedProduct->name_en . ' - Happiness Perfume' }}" loading="lazy"
                                            width="100" height="300">
                                    </a>
                                </div>
                                <div class="product-info">
                                    <a
                                        href="{{ route('product', [app()->getLocale(), $relatedProduct->id, 'size' => $relatedProduct->sizes->first()->size]) }}">
                                        <h3 dir="auto">{{ $relatedProduct->{'name_' . app()->getLocale()} }} -
                                            <span class="size">{{ $relatedProduct->sizes->first()->size }}
                                                {{ __('ml') }}</span>
                                        </h3>
                                    </a>
                                    @if ($relatedProduct->discount_amount > 0)
                                        <div class="product-price">
                                            <span class="discounted-price">{{ $relatedProduct->sizes->first()->price }}
                                                {{ __('EGP') }}</span>
                                            <span>{{ $relatedProduct->priceAfterDiscount }}
                                                {{ __('EGP') }}</span>
                                        </div>
                                    @else
                                        <div class="product-price">{{ $relatedProduct->sizes->first()->price }}
                                            {{ __('EGP') }}
                                        </div>
                                    @endif
                                    <div class="product-actions">
                                        @if ($relatedProduct->sizes->first()->quantity > 0)
                                            <button class="add-to-cart-related" data-id="{{ $relatedProduct->id }}"
                                                data-size="{{ $relatedProduct->sizes->first()->size }}"
                                                onclick="addToCart(this)"><i class="fas fa-cart-plus"></i></button>
                                        @endif
                                        <button
                                            class="add-to-fav-related {{ in_array($relatedProduct->id, $favorites) ? 'favorited' : '' }}"
                                            data-id="{{ $relatedProduct->id }}"
                                            data-size="{{ $relatedProduct->sizes->first()->size }}"><i
                                                class="{{ in_array($relatedProduct->id, $favorites) ? 'fas' : 'far' }} fa-heart"></i></button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Reviews Section -->
            <section class="reviews-section" id="reviewsSection">
            </section>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Product Gallery Thumbnail Navigation
        const thumbnails = document.querySelectorAll('.thumbnail');
        const mainImage = document.getElementById('mainImage');

        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                // Remove active class from all thumbnails
                thumbnails.forEach(t => t.classList.remove('active'));
                // Add active class to clicked thumbnail
                this.classList.add('active');
                // Change main image
                mainImage.src = this.getAttribute('data-image');
            });
        });

        // Star rating hover effect
        const starInputs = document.querySelectorAll('.rating-stars input');
        const starLabels = document.querySelectorAll('.star-label');

        function setStarColor(index) {
            if (index == 0) return;
            starLabels.forEach(l => l.style.color = '#ddd');

            // Fill stars up to the clicked one
            for (let i = 0; i < index; i++) {
                starLabels[i].style.color = 'var(--royal-gold)';
            }

            // Set the corresponding input as checked
            starInputs[index - 1].checked = true;
        }

        setStarColor(@json(data_get($product->reviews, 0)->rate ?? 0));

        // Add click event listeners to star labels
        starLabels.forEach((label, index) => {
            label.addEventListener('click', () => {
                // Clear all stars
                starLabels.forEach(l => l.style.color = '#ddd');

                // Fill stars up to the clicked one
                for (let i = 0; i <= index; i++) {
                    starLabels[i].style.color = 'var(--royal-gold)';
                }

                // Set the corresponding input as checked
                starInputs[index].checked = true;
            });
        });

        starInputs.forEach((input, index) => {
            input.addEventListener('change', () => {
                // Clear all stars
                starLabels.forEach(l => l.style.color = '#ddd');

                // Fill stars up to the selected one
                for (let i = 0; i <= index; i++) {
                    starLabels[i].style.color = 'var(--royal-gold)';
                }
            });

            input.addEventListener('mouseenter', () => {
                // Only change colors if no star is selected or we're hovering over the selected star or higher
                const checkedInput = document.querySelector('input[name="rating"]:checked');
                const checkedIndex = checkedInput ? Array.from(starInputs).indexOf(checkedInput) : -1;

                if (checkedIndex === -1 || index <= checkedIndex) {
                    for (let i = 0; i <= index; i++) {
                        starLabels[i].style.color = 'var(--royal-gold)';
                    }
                }
            });

            input.addEventListener('mouseleave', () => {
                const checkedInput = document.querySelector('input[name="rating"]:checked');
                if (!checkedInput) {
                    starLabels.forEach(label => {
                        label.style.color = '#ddd';
                    });
                } else {
                    // Restore the selected rating
                    const selectedIndex = Array.from(starInputs).indexOf(checkedInput);
                    starLabels.forEach((label, i) => {
                        label.style.color = i <= selectedIndex ? 'var(--royal-gold)' : '#ddd';
                    });
                }
            });
        });

        //get reviews
        const reviewsContainer = document.querySelector('.reviews-section');

        fetch('/{{ app()->getLocale() }}/product/' + {{ request('id') }} + '/reviews', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                reviewsContainer.innerHTML = data.reviews;
                attachPaginationListeners();
            })
            .catch(error => console.error(error));

        // Function to attach event listeners to all pagination links
        function attachPaginationListeners() {
            document.querySelectorAll('.pagination-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = this.getAttribute('data-page');
                    fetch('/{{ app()->getLocale() }}/product/' + {{ request('id') }} +
                            '/reviews?page=' + page, {
                                method: 'GET',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                }
                            })
                        .then(response => response.json())
                        .then(data => {
                            reviewsContainer.innerHTML = data.reviews;
                            // Re-attach event listeners to the new pagination links
                            attachPaginationListeners();
                            // Scroll to the reviews section with a slight delay to ensure DOM is updated
                            setTimeout(() => {
                                const reviewsSection = document.getElementById(
                                    'reviewsSection');
                                if (reviewsSection) {
                                    reviewsSection.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'start'
                                    });
                                }
                            }, 100);
                        })
                        .catch(error => console.error(error));
                });
            });
        }

        // Disable submit button on form submission to prevent multiple clicks
        const reviewForm = document.getElementById('reviewForm');
        if (reviewForm) {
            reviewForm.addEventListener('submit', function() {
                const submitButton = this.querySelector('.submit-review-btn');
                const deleteButton = this.querySelector('.delete-review-btn');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '{{ __('Processing...') }}';
                    submitButton.style.opacity = '0.7';
                    submitButton.style.cursor = 'not-allowed';
                }
                if (deleteButton) {
                    deleteButton.disabled = true;
                    deleteButton.innerHTML = '{{ __('Processing...') }}';
                    deleteButton.style.opacity = '0.7';
                    deleteButton.style.cursor = 'not-allowed';
                }
            });
        }

        // Fixed Action Bar Scroll Logic
        const fixedActionBar = document.getElementById('fixedActionBar');
        const originalActions = document.getElementById('originalActions');
        const luxuryChatWidget = document.querySelector('.luxury-chat');
        const whatsappButton = document.querySelector('.whatsapp-circle');
        
        if (fixedActionBar && originalActions) {
            let lastScrollTop = 0;
            
            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                const originalActionsRect = originalActions.getBoundingClientRect();
                const originalActionsBottom = originalActionsRect.bottom;
                
                // Show fixed bar when original actions are scrolled out of view
                if (originalActionsBottom < 0) {
                    fixedActionBar.classList.add('show');
                    // Push chat and WhatsApp buttons up
                    if (luxuryChatWidget) luxuryChatWidget.classList.add('push-up');
                    if (whatsappButton) whatsappButton.classList.add('push-up');
                } else {
                    fixedActionBar.classList.remove('show');
                    // Return chat and WhatsApp buttons to original position
                    if (luxuryChatWidget) luxuryChatWidget.classList.remove('push-up');
                    if (whatsappButton) whatsappButton.classList.remove('push-up');
                }
                
                lastScrollTop = scrollTop;
            });
        }
    </script>

    <!-- Product Schema.org Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "Product",
        "name": "{{ $product->{'name_' . app()->getLocale()} }}",
        "image": [
            "{{ $product->main_image }}"
        ],
        "description": "{{ strip_tags($product->{'description_' . app()->getLocale()}) }}",
        "sku": "PERFUME-{{ $product->id }}",
        "mpn": "{{ $product->id }}",
        "brand": {
            "@type": "Brand",
            "name": "Happiness Perfume"
        },
        "offers": {
            "@type": "Offer",
            "url": "{{ url()->current() }}",
            "priceCurrency": "EGP",
            "price": "{{ $product->sizes->where('size', request()->size)->first()->price }}",
            "priceValidUntil": "{{ now()->addYear()->format('Y-m-d') }}",
            "availability": "{{ $product->sizes->where('size', request()->size)->first()->quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
            "itemCondition": "https://schema.org/NewCondition",
            "seller": {
                "@type": "Organization",
                "name": "Happiness Perfume"
            }
        }
        @if($product->reviews->count() > 0)
        ,"aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "{{ number_format($product->reviews->avg('rating'), 1) }}",
            "reviewCount": "{{ $product->reviews->count() }}",
            "bestRating": "5",
            "worstRating": "1"
        }
        @endif
    }
    </script>
@endsection
