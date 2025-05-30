@extends('layouts.app')

@section('title', $product->name_en . ' - ' . $product->name_ar)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">

    @if (app()->getLocale() === 'ar')
        <link rel="stylesheet" href="{{ asset('css/product-ar.css') }}">
    @endif
@endsection

@section('content')
    <!-- Product Main Section -->
    <div class="product-page">

        <div class="container">
            <div class="product-container">
                <div class="product-gallery">
                    @if ($product->tag_ar)
                        <div class="product-badge">{{ $product->{'tag_' . app()->getLocale()} }}</div>
                    @endif
                    <img src="{{ $product->main_image }}" alt="{{ $product->name_en }}" class="main-image" id="mainImage"
                        loading="lazy">

                    <div class="thumbnail-container">
                        @foreach ($product->allImages as $image)
                            @if ($loop->first)
                                <img src="{{ $image }}" alt="{{ $product->name_en }}" class="thumbnail active"
                                    data-image="{{ $image }}" loading="lazy">
                            @else
                                @if ($image)
                                    <img src="{{ $image }}" alt="{{ $product->name_en }}" class="thumbnail"
                                        data-image="{{ $image }}" loading="lazy">
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="product-details">
                    <h1 class="product-title">{{ $product->{'name_' . app()->getLocale()} }}</h1>
                    <span class="product-category">{{ $product->category->{'name_' . app()->getLocale()} }}</span>

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

                    <div class="product-actions">
                        <button class="add-to-cart" data-id="{{ $product->id }}" data-size="{{ request()->size }}"
                            onclick="addToCart(this)"><i class="fas fa-cart-plus"></i></button>
                        <button class="add-to-fav {{ in_array($product->id, $favorites) ? 'favorited' : '' }}"
                            data-id="{{ $product->id }}" data-size="{{ request()->size }}"><i
                                class="{{ in_array($product->id, $favorites) ? 'fas' : 'far' }} fa-heart"></i></button>
                    </div>
                </div>
            </div>

            <p class="product-description">
                {!! $product->{'description_' . app()->getLocale()} !!}
            </p>

            <!-- Review Form -->
            {{-- <div class="review-form-container">
				<h3 class="review-form-title">{{ __('Add Your Review') }}</h3>
				<form id="reviewForm" class="review-form">
					<div class="form-group">
						<label class="form-label">{{ __('Your Rating') }}</label>
						<div class="rating-stars">
							<input type="radio" id="star5" name="rating" value="5">
							<label for="star5" class="star-label"><i class="fas fa-star"></i></label>

							<input type="radio" id="star4" name="rating" value="4">
							<label for="star4" class="star-label"><i class="fas fa-star"></i></label>

							<input type="radio" id="star3" name="rating" value="3">
							<label for="star3" class="star-label"><i class="fas fa-star"></i></label>

							<input type="radio" id="star2" name="rating" value="2">
							<label for="star2" class="star-label"><i class="fas fa-star"></i></label>

							<input type="radio" id="star1" name="rating" value="1">
							<label for="star1" class="star-label"><i class="fas fa-star"></i></label>
						</div>
					</div>

					<div class="form-group">
						<label for="reviewText" class="form-label">{{ __('Your Review') }}</label>
						<textarea id="reviewText" class="form-textarea" rows="5" required></textarea>
					</div>

					<button type="submit" class="submit-review-btn">{{ __('Submit Review') }}</button>
				</form>
			</div> --}}

            <!-- Reviews Section -->
            {{-- <section class="reviews-section">
				<div class="section-title">
					<h2>{{ __('Customer Reviews') }}</h2>
				</div>

				<div class="reviews-container">
					<!-- Review 1 -->
					<div class="review-card">
						<div class="review-header">
							<div class="reviewer">
								<img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Sarah Mohamed"
									class="reviewer-avatar">
								<div>
									<div class="reviewer-name">Sarah Mohamed</div>
									<div class="review-date">March 15, 2023</div>
								</div>
							</div>
							<div class="review-rating">
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
							</div>
						</div>
						<div class="review-content">
							The perfume is wonderful! The scent lasts all day and I receive many compliments.
							The packaging was beautiful and the bottle is very elegant. I will definitely buy it again.
						</div>
					</div>

					<!-- Review 2 -->
					<div class="review-card">
						<div class="review-header">
							<div class="reviewer">
								<img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Nora Ahmed"
									class="reviewer-avatar">
								<div>
									<div class="reviewer-name">Nora Ahmed</div>
									<div class="review-date">February 2, 2023</div>
								</div>
							</div>
							<div class="review-rating">
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="far fa-star"></i>
							</div>
						</div>
						<div class="review-content">
							The scent is very beautiful and fresh, but I wish it lasted longer.
							The delivery service was fast and the product arrived in excellent condition.
						</div>
					</div>

					<!-- Review 3 -->
					<div class="review-card">
						<div class="review-header">
							<div class="reviewer">
								<img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Lama Khaled"
									class="reviewer-avatar">
								<div>
									<div class="reviewer-name">Lama Khaled</div>
									<div class="review-date">January 28, 2023</div>
								</div>
							</div>
							<div class="review-rating">
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star-half-alt"></i>
							</div>
						</div>
						<div class="review-content">
							This perfume has become my favorite! The scent is perfect for day and evening,
							a wonderful mix of flowers and vanilla. I recommend it to anyone looking for an elegant
							feminine perfume.
						</div>
					</div>
				</div>

				<!-- Pagination -->
				<div class="pagination">
					<ul class="pagination-list">
						<li class="pagination-item">
							<a href="#"><i class="fas fa-chevron-left"></i></a>
						</li>
						<li class="pagination-item active">
							<a href="#">1</a>
						</li>
						<li class="pagination-item">
							<a href="#">2</a>
						</li>
						<li class="pagination-item">
							<a href="#">3</a>
						</li>
						<li class="pagination-item">
							<a href="#"><i class="fas fa-chevron-right"></i></a>
						</li>
					</ul>
				</div>
			</section> --}}

            <!-- Related Products -->
            @if ($relatedProducts->isNotEmpty())
                <section class="related-products">
                    <div class="section-title">
                        <h2>{{ __('Related Products') }}</h2>
                    </div>

                    <div class="related-slider">
                        @if (app()->getLocale() == 'ar')
                            <div class="slider-nav slider-prev">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        @else
                            <div class="slider-nav slider-next">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        @endif

                        <div class="related-slider-container" id="relatedSlider">
                            @foreach ($relatedProducts as $product)
                                <div class="related-product-card">
                                    <div class="related-product-img">
                                        <a
                                            href="{{ route('product', [app()->getLocale(), $product->id, 'size' => $product->sizes->first()->size]) }}">
                                            <img src="{{ $product->main_image }}"
                                                alt="{{ $product->{'name_' . app()->getLocale()} }}" loading="lazy"
                                                width="270" height="250">
                                        </a>
                                    </div>
                                    <div class="related-product-info">
                                        <a
                                            href="{{ route('product', [app()->getLocale(), $product->id, 'size' => $product->sizes->first()->size]) }}">
                                            <h3>{{ $product->{'name_' . app()->getLocale()} }}</h3>
                                        </a>
                                        <span>{{ $product->sizes->first()->size }} {{ __('ml') }}</span>
                                        @if ($product->discount_amount > 0)
                                            <div class="related-product-price">
                                                <span>{{ $product->sizes->first()->price }} {{ __('EGP') }}</span>
                                                <span class="discounted-price">{{ $product->priceAfterDiscount }}
                                                    {{ __('EGP') }}</span>
                                            </div>
                                        @else
                                            <div class="related-product-price">{{ $product->sizes->first()->price }}
                                                {{ __('EGP') }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if (app()->getLocale() == 'ar')
                            <div class="slider-nav slider-next">
                                <i class="fas fa-chevron-left"></i>
                            </div>
                        @else
                            <div class="slider-nav slider-prev">
                                <i class="fas fa-chevron-left"></i>
                            </div>
                        @endif
                    </div>
                </section>
            @endif
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

        // Related Products Slider
        const slider = document.getElementById('relatedSlider');
        const prevBtn = document.querySelector('.slider-prev');
        const nextBtn = document.querySelector('.slider-next');

        @if (app()->getLocale() == 'ar')
            prevBtn.addEventListener('click', () => {
                slider.scrollBy({
                    left: 300,
                    behavior: 'smooth'
                });
            });

            nextBtn.addEventListener('click', () => {
                slider.scrollBy({
                    left: -300,
                    behavior: 'smooth'
                });
            });
        @endif

        @if (app()->getLocale() == 'en')
            nextBtn.addEventListener('click', () => {
                slider.scrollBy({
                    left: 300,
                    behavior: 'smooth'
                });
            });

            prevBtn.addEventListener('click', () => {
                slider.scrollBy({
                    left: -300,
                    behavior: 'smooth'
                });
            });
        @endif

        // Pagination
        const paginationItems = document.querySelectorAll('.pagination-item:not(:first-child):not(:last-child)');

        paginationItems.forEach(item => {
            item.addEventListener('click', () => {
                document.querySelector('.pagination-item.active').classList.remove('active');
                item.classList.add('active');
                // Here you would load the corresponding reviews page
            });
        });

        // Review Form Submission
        const reviewForm = document.getElementById('reviewForm');

        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const name = document.getElementById('reviewerName').value;
            const rating = document.querySelector('input[name="rating"]:checked')?.value;
            const reviewText = document.getElementById('reviewText').value;

            if (!rating) {
                alert('Please select a rating from 1 to 5 stars');
                return;
            }

            // Here you would typically send the data to your server
            console.log('New Review:', {
                name,
                rating,
                reviewText
            });

            // For demo purposes, we'll add the review to the page
            addReviewToPage(name, rating, reviewText);

            // Reset the form
            reviewForm.reset();

            // Scroll to the new review
            document.querySelector('.reviews-section').scrollIntoView({
                behavior: 'smooth'
            });

            alert('Thank you for your review! Your review has been successfully added.');
        });

        function addReviewToPage(name, rating, text) {
            const reviewsContainer = document.querySelector('.reviews-container');
            const now = new Date();
            const dateString = now.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const reviewHTML = `
			<div class="review-card">
				<div class="review-header">
					<div class="reviewer">
						<img src="https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=random"
							alt="${name}" class="reviewer-avatar">
						<div>
							<div class="reviewer-name">${name}</div>
							<div class="review-date">${dateString}</div>
						</div>
					</div>
					<div class="review-rating">
						${'<i class="fas fa-star"></i>'.repeat(rating)}
						${'<i class="far fa-star"></i>'.repeat(5 - rating)}
					</div>
				</div>
				<div class="review-content">${text}</div>
			</div>
		`;

            reviewsContainer.insertAdjacentHTML('afterbegin', reviewHTML);
        }

        // Star rating hover effect
        const starInputs = document.querySelectorAll('.rating-stars input');
        const starLabels = document.querySelectorAll('.star-label');

        starInputs.forEach((input, index) => {
            input.addEventListener('mouseenter', () => {
                starLabels.forEach((label, labelIndex) => {
                    if (labelIndex <= index) {
                        label.style.color = 'var(--royal-gold)';
                    }
                });
            });

            input.addEventListener('mouseleave', () => {
                const checkedInput = document.querySelector('input[name="rating"]:checked');
                if (!checkedInput) {
                    starLabels.forEach(label => {
                        label.style.color = '#ddd';
                    });
                }
            });
        });
    </script>
@endsection
