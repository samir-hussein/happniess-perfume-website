@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/order.css') }}?v={{ filemtime(public_path('css/order.css')) }}">

    @if (app()->getLocale() === 'ar')
        <link rel="stylesheet" href="{{ asset('css/order-ar.css') }}?v={{ filemtime(public_path('css/order-ar.css')) }}">
    @endif
@endsection

@section('content')
    <!-- Account Navigation -->
    <div class="account-nav">
        <div class="container account-nav-container">
            <div class="account-welcome">{{ __('Welcome back') }}, <span>{{ auth()->user()->name }}</span></div>
        </div>
    </div>

    <!-- Orders Section -->
    <section class="orders-section">
        <div class="container">
            <h1 class="section-title">{{ __('My Orders') }}</h1>

            <!-- Search Order Form -->
            <div class="search-order">
                <input type="text" class="search-order-input" id="orderSearch"
                    placeholder="{{ __('Search by order number') }}" value="{{ request('search') }}">
                <button class="search-order-btn" id="searchOrderBtn">
                    <i class="fas fa-search"></i>
                    {{ __('Search') }}
                </button>
            </div>

            <div class="order-tabs">
                <div class="order-tab {{ request()->status == null ? 'active' : '' }}" data-value="">
                    {{ __('All Orders') }}
                    <span class="order-tab-count">{{ $ordersCount['all'] }}</span>
                </div>
                <div class="order-tab {{ request()->status == 'pending' ? 'active' : '' }}" data-value="pending">
                    {{ __('Pending') }} <span class="order-tab-count">{{ $ordersCount['pending'] }}</span></div>
                <div class="order-tab {{ request()->status == 'processing' ? 'active' : '' }}" data-value="processing">
                    {{ __('Processing') }} <span class="order-tab-count">{{ $ordersCount['processing'] }}</span></div>
                <div class="order-tab {{ request()->status == 'shipped' ? 'active' : '' }}" data-value="shipped">
                    {{ __('Shipped') }} <span class="order-tab-count">{{ $ordersCount['shipped'] }}</span></div>
                <div class="order-tab {{ request()->status == 'delivered' ? 'active' : '' }}" data-value="delivered">
                    {{ __('Delivered') }} <span class="order-tab-count">{{ $ordersCount['delivered'] }}</span></div>
                <div class="order-tab {{ request()->status == 'cancelled' ? 'active' : '' }}" data-value="cancelled">
                    {{ __('Cancelled') }} <span class="order-tab-count">{{ $ordersCount['cancelled'] }}</span></div>
            </div>

            <!-- Order List -->
            @foreach ($orders as $order)
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-info">
                            <div class="order-info-item">
                                <span class="order-info-label">{{ __('Order Number') }}</span>
                                <span class="order-info-value">{{ $order->order_number }}</span>
                            </div>
                            <div class="order-info-item">
                                <span class="order-info-label">{{ __('Date') }}</span>
                                <span class="order-info-value">{{ $order->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="order-info-item">
                                <span class="order-info-label">{{ __('Discount') }}</span>
                                <span class="order-info-value">{{ $order->discount_amount }} {{ __('EGP') }}</span>
                            </div>
                            <div class="order-info-item">
                                <span class="order-info-label">{{ __('Shipping') }}</span>
                                <span class="order-info-value">{{ $order->shipping_cost }} {{ __('EGP') }}</span>
                            </div>
                            <div class="order-info-item">
                                <span class="order-info-label">{{ __('Total') }}</span>
                                <span class="order-info-value">{{ $order->total_price }} {{ __('EGP') }}</span>
                            </div>
                        </div>

                        <div class="order-status status-{{ $order->order_status }}">{{ __($order->order_status) }}</div>
                    </div>
                    <div class="order-body">
                        <div class="order-items">
                            @foreach ($order->orderItems as $orderItem)
                                <div class="order-item">
                                    <div class="order-item-img">
                                        <a
                                            href="{{ route('product', [app()->getLocale(), $orderItem->product->id, 'size' => $orderItem->size]) }}">
                                            <img src="{{ $orderItem->product->main_image }}"
                                                alt="{{ $orderItem->product->name_ . app()->getLocale() }}" loading="lazy">
                                        </a>
                                    </div>
                                    <div class="order-item-details">
                                        <div class="order-item-name">
                                            <a
                                                href="{{ route('product', [app()->getLocale(), $orderItem->product->id, 'size' => $orderItem->size]) }}">
                                                {{ $orderItem->product->{'name_' . app()->getLocale()} }}
                                            </a>
                                        </div>
                                        <div class="order-item-price">{{ $orderItem->price }} {{ __('EGP') }}
                                        </div>
                                        <div class="order-item-qty">{{ __('Size') }} : {{ $orderItem->size }}
                                            {{ __('ml') }}</div>
                                        <div class="order-item-qty">{{ __('Qty') }} : {{ $orderItem->quantity }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="order-total">
                            <div class="order-total-label">{{ __('Subtotal') }}</div>
                            <div class="order-total-value">{{ $order->sub_total_price }} {{ __('EGP') }}</div>
                        </div>
                        <div class="order-actions">
                            @if ($order->payment_link)
                                <a href="{{ route('order.invoice', $order->id) }}" rel="noopener noreferrer"
                                    class="order-btn order-btn-primary" target="_blank">{{ __('Invoice') }}</a>
                            @endif
                            @if ($order->order_status != 'cancelled')
                                <button class="order-btn order-btn-primary track-order-btn"
                                    data-order="{{ $order->id }}">{{ __('Track Order') }}</button>
                            @endif
                            @if ($order->order_status === 'delivered' || $order->order_status === 'cancelled')
                                <form action="{{ route('order.reorder', [app()->getLocale(), $order->id]) }}"
                                    method="POST">
                                    @csrf
                                    <button class="order-btn order-btn-primary">{{ __('Reorder') }}</button>
                                </form>
                            @endif
                            @if ($order->order_status === 'pending' && $order->payment_method == 'cash_on_delivery')
                                <form action="{{ route('order.cancel', [app()->getLocale(), $order->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button class="order-btn order-btn-secondary">{{ __('Cancel Order') }}</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            @if ($orders->hasPages())
                <div class="pagination">
                    <ul class="pagination-list">
                        <li class="pagination-item">
                            <a href="{{ $orders->appends(request()->except('page'))->previousPageUrl() }}" id="prevPage"
                                class="pagination-link"><i
                                    class="fas {{ app()->getLocale() === 'ar' ? 'fa-chevron-right' : 'fa-chevron-left' }}"></i></a>
                        </li>
                        @php
                            $currentPage = $orders->currentPage();
                            $lastPage = $orders->lastPage();
                        @endphp

                        <li class="pagination-item {{ $currentPage == 1 ? 'active' : '' }}">
                            {{-- Always show page 1 --}}
                            <a href="{{ $orders->appends(request()->except('page'))->url(1) }}">1</a>
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
                                    href="{{ $orders->appends(request()->except('page'))->url($i) }}">{{ $i }}</a>
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
                                    href="{{ $orders->appends(request()->except('page'))->url($lastPage - 1) }}">{{ $lastPage - 1 }}</a>
                            </li>
                        @endif

                        {{-- Always show last page if it's not 1 --}}
                        @if ($lastPage > 1)
                            <li class="pagination-item {{ $currentPage == $lastPage ? 'active' : '' }}">
                                <a
                                    href="{{ $orders->appends(request()->except('page'))->url($lastPage) }}">{{ $lastPage }}</a>
                            </li>
                        @endif

                        <li class="pagination-item">
                            <a href="{{ $orders->appends(request()->except('page'))->nextPageUrl() }}" id="nextPage"
                                class="pagination-link"><i
                                    class="fas {{ app()->getLocale() === 'ar' ? 'fa-chevron-left' : 'fa-chevron-right' }}"></i></a>
                        </li>
                    </ul>
                </div>
            @endif

            @if ($orders->isEmpty())
                <!-- No Orders State (hidden by default) -->
                <div class="no-orders">
                    <div class="no-orders-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h2 class="no-orders-title">{{ __('No Orders Yet') }}</h2>
                    <p class="no-orders-text">
                        {{ __('You haven\'t placed any orders with us yet. Start exploring our exquisite fragrance collection to find your perfect scent.') }}
                    </p>
                    <a href="{{ route('products', app()->getLocale()) }}" class="no-orders-btn">{{ __('Shop Now') }}</a>
                </div>
            @endif
        </div>
    </section>

    <div class="modal" id="trackingModal">
        <div class="modal-content" id="modalContent">
            <span class="close-modal" id="closeModal">&times;</span>
            <div id="orderLog">

            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Tab functionality
        const orderTabs = document.querySelectorAll('.order-tab');
        const searchBtn = document.querySelector('#searchOrderBtn');

        orderTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Get current URL and create a URL object
                const url = new URL(window.location.href);
                // Set the status parameter
                url.searchParams.set('status', this.dataset.value);
                url.searchParams.set('page', 1);
                // Navigate to the new URL
                window.location.href = url.toString();
            });
        });

        searchBtn.addEventListener('click', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('search', document.querySelector('#orderSearch').value);
            window.location.href = url.toString();
        });

        // make search input enter key to trigger search
        document.querySelector('#orderSearch').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                searchBtn.click();
            }
        });

        const modal = document.getElementById('trackingModal');
        const closeModal = document.getElementById('closeModal');
        const trackOrderBtns = document.querySelectorAll('.track-order-btn');
        const modalContent = document.getElementById('orderLog');

        trackOrderBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                fetch(`/{{ app()->getLocale() }}/order/logs/${e.target.dataset.order}`)
                    .then(response => response.text())
                    .then(data => {
                        modalContent.innerHTML = data;
                        modal.style.display = 'block';
                        document.body.style.overflow = 'hidden'; // Prevent scrolling
                    });
            });
        });

        // Close modal when X is clicked
        closeModal.addEventListener('click', function() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        // Close modal when clicking outside the content
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && modal.style.display === 'block') {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
    </script>
@endsection
