<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Happiness Perfume | عطور السعادة')</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}" />
    <meta name="apple-mobile-web-app-title" content='Happiness Perfume | عطور السعادة' />
    <link rel="manifest" href="{{ asset('site.webmanifest') }}" />
    <meta name="author" content="Samir Hussein">
    <meta name="robots" content="index, follow">
    <meta name="description" content="@yield('description', 'Happiness Perfume offers premium, luxurious scents for every occasion. Explore our fine fragrances and elevate your everyday moments.')">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://Happiness-perfume.com/" />
    <meta property="og:title" content="Happiness Perfume | عطور السعادة" />
    <meta property="og:description"
        content="Happiness Perfume offers premium, luxurious scents for every occasion. Explore our fine fragrances and elevate your everyday moments." />
    <meta property="og:image" content="{{ asset('images/meta.jpg') }}" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="https://Happiness-perfume.com/" />
    <meta property="twitter:title" content="Happiness Perfume | عطور السعادة" />
    <meta property="twitter:description"
        content="Happiness Perfume offers premium, luxurious scents for every occasion. Explore our fine fragrances and elevate your everyday moments." />
    <meta property="twitter:image" content="{{ asset('images/meta.jpg') }}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Parisienne&family=Dancing+Script:wght@700&family=Tajawal:wght@400;500;700&family=Montserrat:wght@300;400;500&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @if (app()->getLocale() === 'ar')
        <link rel="stylesheet" href="{{ asset('css/app-ar.css') }}">
    @endif
    @yield('styles')
</head>

<body>
    @include('Includes.navbar')

    <!-- Cart Side Panel -->
    @include('Includes.cart-panel')

    <div class="overlay"></div>

    <div class="toast-container" id="toastContainer">
        @if (session('error'))
            <div class="toast error show errorToast">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('success'))
            <div class="toast success show" id="successToast">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="toast error show errorToast">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $error }}</span>
                </div>
            @endforeach
        @endif
    </div>


    @yield('content')

    @include('Includes.footer')

    <script>
        window.authUser = @json(Auth::check() ? Auth::user() : null);
        window.translations = {
            'product_added_to_cart': '{{ __('Product added to cart!') }}',
            'remove': '{{ __('Remove') }}',
            'ml': '{{ __('ml') }}',
            'egp': '{{ __('EGP') }}',
            'total': '{{ __('Total') }}',
            'product_removed_from_cart': '{{ __('Product removed from cart!') }}',
            'proceed_to_checkout': '{{ __('Proceed to Checkout') }}',
            'product_quantity_updated': '{{ __('Product quantity updated!') }}',
        };
    </script>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>

</html>
