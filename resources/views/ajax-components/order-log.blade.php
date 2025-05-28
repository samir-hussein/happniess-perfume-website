<div class="tracking-section">
    <div class="tracking-header">
        <h2 class="tracking-title">{{ __('Order Tracking') }}</h2>
        <div class="tracking-number">{{ __('Order Number') }}: <span
                id="modalOrderNumber">{{ $order->order_number }}</span>
        </div>
    </div>

    <div class="tracking-progress">
        <div class="tracking-progress-bar" style="width: {{ $progress }};"></div>
        <div class="tracking-steps">
            <div class="tracking-step completed">
                <div class="tracking-step-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="tracking-step-label">{{ __('Order Placed') }}</div>
                <div class="tracking-step-date">{{ $statusDates['pending'] }}</div>
            </div>
            <div class="tracking-step completed">
                <div class="tracking-step-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="tracking-step-label">{{ __('Processing') }}</div>
                <div class="tracking-step-date">{{ $statusDates['processing'] }}</div>
            </div>
            <div class="tracking-step active">
                <div class="tracking-step-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="tracking-step-label">{{ __('Shipped') }}</div>
                <div class="tracking-step-date">{{ $statusDates['shipped'] }}</div>
            </div>
            <div class="tracking-step">
                <div class="tracking-step-icon">
                    <i class="fas fa-home"></i>
                </div>
                <div class="tracking-step-label">{{ __('Delivered') }}</div>
                <div class="tracking-step-date">{{ $statusDates['delivered'] }}</div>
            </div>
        </div>
    </div>

    <div class="tracking-details">
        @foreach ($orderLogs as $orderLog)
            <div class="tracking-detail">
                <div class="tracking-detail-icon">
                    <i class="fas fa-{{ $icons[$orderLog->action_en] }}"></i>
                </div>
                <div class="tracking-detail-content">
                    <div class="tracking-detail-title">{{ $orderLog->{'action_' . app()->getLocale()} }}</div>
                    <div class="tracking-detail-description">{{ $orderLog->{'description_' . app()->getLocale()} }}
                    </div>
                    <div class="tracking-detail-time">{{ $orderLog->created_at->format('M d, Y') }}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>
