<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Happiness Perfume</title>
    <meta name="robots" content="noindex, follow">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --royal-gold: #D4AF37;
            --deep-bronze: #8C6A25;
            --warm-beige: #F7E5B7;
            --ivory-white: #FDFBF6;
            --charcoal-black: #1C1C1C;
            --golden-shadow: #B8860B;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Playfair Display', serif;
        }

        body {
            background-color: var(--ivory-white);
            color: var(--charcoal-black);
            line-height: 1.6;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: var(--royal-gold);
            color: var(--ivory-white);
            border: 2px solid var(--royal-gold);
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
        }

        .btn:hover {
            background-color: transparent;
            color: var(--royal-gold);
        }

        /* Confirmation specific styles */
        .confirmation-container {
            text-align: center;
            padding: 80px 20px;
        }

        .confirmation-icon {
            font-size: 80px;
            color: var(--royal-gold);
            margin-bottom: 30px;
        }

        .confirmation-message {
            max-width: 600px;
            margin: 0 auto;
        }

        .confirmation-message h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: var(--deep-bronze);
        }

        .confirmation-message p {
            margin-bottom: 30px;
            font-size: 18px;
        }

        .order-details {
            background-color: var(--warm-beige);
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            margin: 40px auto;
            text-align: left;
        }

        .order-details h3 {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }

        .order-detail {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <!-- Header & Navigation (same as main page) -->

    <!-- Confirmation Content -->
    <div class="container">
        <div class="confirmation-container">
            <div class="confirmation-icon">
                <i class="fas fa-check-circle"></i>
            </div>

            <div class="confirmation-message">
                <h1>{{ __('Thank You for Your Order!') }}</h1>
                <p>{{ __('Your order has been placed successfully. Please take this page screenshot and keep it for your records') }}
                </p>

                <div class="order-details">
                    <h3>{{ __('Order Information') }}</h3>
                    <div class="order-detail">
                        <span>{{ __('Order Number') }}:</span>
                        <span id="order-number">{{ $order->order_number }}</span>
                    </div>
                    <div class="order-detail">
                        <span>{{ __('Date') }}:</span>
                        <span id="order-date">{{ $order->created_at }}</span>
                    </div>
                    <div class="order-detail">
                        <span>{{ __('Total') }}:</span>
                        <span id="order-total">{{ $order->total_price }} {{ __('EGP') }}</span>
                    </div>
                    <div class="order-detail">
                        <span>{{ __('Payment Method') }}:</span>
                        <span id="payment-method">{{ __($order->payment_method) }}</span>
                    </div>
                </div>

                <a href="{{ route('home', app()->getLocale()) }}" class="btn">{{ __('Continue Shopping') }}</a>
            </div>
        </div>
    </div>
</body>

</html>
