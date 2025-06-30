<div class="section-title">
    <h2>{{ __('Customer Reviews') }}</h2>
</div>

<div class="reviews-container">
    @if ($reviews->isEmpty())
        <div class="empty-reviews">
            {{ __('No reviews yet.') }}
        </div>
    @else
        @foreach ($reviews as $review)
            <div class="review-card">
                <div class="review-header">
                    <div class="reviewer">
                        <div>
                            <div class="reviewer-name">
                                <span>{{ $review->client->name }}</span>
                                <span>{{ $review->client->email }}</span>
                            </div>
                            <div class="review-date">{{ $review->created_at->format('F j, Y') }}</div>
                        </div>
                    </div>
                    <div class="review-rating">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $review->rate)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                </div>
                <div class="review-content" dir="auto">
                    {{ $review->comment }}
                </div>
            </div>
        @endforeach
    @endif
</div>

<!-- Pagination -->
@if ($reviews->hasPages())
    <div class="pagination">
        <ul class="pagination-list">
            @php
                $currentPage = $reviews->currentPage();
            @endphp

            <li class="pagination-item">
                <a href="{{ $reviews->appends(request()->except('page'))->previousPageUrl() }}" id="prevPage"
                    class="pagination-link" data-page="{{ $reviews->currentPage() - 1 }}"
                    data-direction="{{ app()->getLocale() }}"><i
                        class="fas {{ app()->getLocale() === 'ar' ? 'fa-chevron-right' : 'fa-chevron-left' }}"></i></a>
            </li>

            <li class="pagination-item">
                <a href="{{ $reviews->appends(request()->except('page'))->nextPageUrl() }}" id="nextPage"
                    class="pagination-link"
                    data-page="{{ $reviews->lastPage() > $reviews->currentPage() ? $reviews->currentPage() + 1 : $reviews->lastPage() }}"><i
                        class="fas {{ app()->getLocale() === 'ar' ? 'fa-chevron-left' : 'fa-chevron-right' }}"></i></a>
            </li>
        </ul>
    </div>
@endif
