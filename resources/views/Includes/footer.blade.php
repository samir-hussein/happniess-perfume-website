<!-- Footer -->
<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-column">
                <h3>{{ __('Happiness Perfume') }}</h3>
                <p>{{ __('Creating fragrances that evoke joy and lasting memories. Our perfumes are crafted with the finest ingredients to bring Happiness to your everyday life.') }}
                </p>
                <div class="social-links">
                    <a href="https://www.facebook.com/share/1PVPUd4v2x/" target="_blank" rel="noopener noreferrer"><i
                            class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/Happniess_perfume" target="_blank" rel="noopener noreferrer"><i
                            class="fab fa-instagram"></i></a>
                    <a href="https://www.tiktok.com/@happniess.perfume" target="_blank" rel="noopener noreferrer"><i
                            class="fab fa-tiktok"></i></a>
                </div>
            </div>

            <div class="footer-column">
                <h3>{{ __('Quick Links') }}</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('home', app()->getLocale()) }}">{{ __('Home') }}</a></li>
                    <li><a href="{{ route('order.index', app()->getLocale()) }}">{{ __('My Orders') }}</a></li>
                    <li><a href="{{ route('products', app()->getLocale()) }}">{{ __('Products') }}</a></li>
                    <li><a href="{{ route('favorite', app()->getLocale()) }}">{{ __('Favorites') }}</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h3>{{ __('Customer Service') }}</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('shipping-policy', app()->getLocale()) }}">{{ __('Shipping Policy') }}</a>
                    </li>
                    <li><a href="{{ route('return-policy', app()->getLocale()) }}">{{ __('Return Policy') }}</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h3>{{ __('Contact Us') }}</h3>
                <p><i class="fas fa-map-marker-alt"></i>
                    <a href="https://maps.app.goo.gl/yegbkKnGTPZHSJ1M7" target="_blank"
                        rel="noopener noreferrer">{{ __('Cairo, Manshiyet Nasser, Autostrad Road, Al-Mazlaqan Station') }}</a>
                </p>
                <p><i class="fab fa-whatsapp"></i> <a target="_blank" rel="noopener noreferrer"
                        href="https://wa.me/+201011796422">01011796422</a>
                </p>
                <p><i class="fas fa-clock"></i> {{ __('Every day from 3 PM to 12 AM.') }}</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p dir="ltr">&copy; 2025 Happiness Perfume. All Rights Reserved. Designed & Developed by <a
                    target="_blank" rel="noopener noreferrer" href="https://wa.me/+201144435326">Samir Hussein</a> .</p>
        </div>
    </div>
</footer>
