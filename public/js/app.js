// Mobile Menu Toggle
const menuToggle = document.querySelector('.menu-toggle');
const navLinks = document.querySelector('.nav-links');
const dropdownBtns = document.querySelectorAll('.dropdown-btn');
const locale = document.documentElement.lang || '{{ app()->getLocale() }}';

menuToggle.addEventListener('click', () => {
	navLinks.classList.toggle('active');
	menuToggle.innerHTML = navLinks.classList.contains('active') ?
		'<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
});

dropdownBtns.forEach(btn => {
	btn.addEventListener('click', () => {
		const dropdown = btn.parentElement;
		dropdown.classList.toggle('active');
	});
});

// Cart Panel Toggle
const cartIcon = document.getElementById('cart-icon');
const cartPanel = document.querySelector('.cart-panel');
const closeCart = document.querySelector('.close-cart');
const overlay = document.querySelector('.overlay');

cartIcon.addEventListener('click', () => {
	getCartProducts();
	cartPanel.classList.add('active');
	overlay.classList.add('active');
});

closeCart.addEventListener('click', () => {
	cartPanel.classList.remove('active');
	overlay.classList.remove('active');
});

overlay.addEventListener('click', () => {
	cartPanel.classList.remove('active');
	overlay.classList.remove('active');
});

// Favorite Toggle
const favButtons = document.querySelectorAll('.add-to-fav');

favButtons.forEach(button => {
	button.addEventListener('click', function() {
		if (!window.authUser) {
			window.location.href = '/' + locale + '/login';
			return;
		}
		this.classList.toggle('favorited');
		const icon = this.querySelector('i');

		if (this.classList.contains('favorited')) {
			icon.classList.remove('far');
			icon.classList.add('fas');
		} else {
			icon.classList.remove('fas');
			icon.classList.add('far');
		}

		addToFavorites(this);
	});
});

// Add to favorites function
function addToFavorites(button) {
	const productId = button.getAttribute('data-id');
	const size = button.getAttribute('data-size');

	fetch('/favorite/add', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'Accept': 'application/json',
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
		},
		body: JSON.stringify({
			product_id: productId,
			size: size,
		})
	})
	.then(response => {
		if (response.ok) {
			// check if page is favorite
			if (window.location.pathname.split('/').pop() === 'favorite') {
				window.location.reload();
			}
			updateFavoriteCount();
		}
	})
	.catch(error => console.error(error));
}

function addToCart(e) {
	if (window.authUser) {
		// User is logged in
		addToCartLoggedUser(e);
	} else {
		// Guest user
		addToCartLocalStorage(e);
	}
	showToast(window.translations.product_added_to_cart, 'success');
}

function addToCartLocalStorage(e) {
	let cart = JSON.parse(localStorage.getItem('cart')) || [];
	let productId = e.getAttribute('data-id');
	let size = e.getAttribute('data-size');

	const existingItem = cart.find(item => item.product_id == productId && item.size == size);
	if (existingItem) {
		existingItem.quantity += 1;
	} else {
		cart.push({ product_id: productId, size: size, quantity: 1 });
	}

	localStorage.setItem('cart', JSON.stringify(cart));
	updateCartCount();
}

function addToCartLoggedUser(e) {
	let productId = e.getAttribute('data-id');
	let size = e.getAttribute('data-size');

	fetch('/cart/add', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'Accept': 'application/json',
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
		},
		body: JSON.stringify({
			product_id: productId,
			size: size,
			quantity: 1,
		})
	})
	.then(response => response.json())
	.then(data => {
		// update cart count
		updateCartCount();
	})
	.catch(error => console.error(error));
}

updateCartCount();

function updateLocalStorageCartCount() {
	let cart = JSON.parse(localStorage.getItem('cart')) || [];
	let cartCount = document.querySelector('.cart-count');
	cartCount.textContent = cart.reduce((total, item) => total + item.quantity, 0);
	cartCount.style.display = cart.length > 0 ? 'flex' : 'none';
}

function updateLoggedUserCartCount() {
	fetch('/cart/count', {
		method: 'GET',
		headers: {
			'Content-Type': 'application/json',
			'Accept': 'application/json',
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
		}
	})
	.then(response => response.json())
	.then(data => {
		let count = parseInt(data, 10) || 0;
		let cartCount = document.querySelector('.cart-count');
		cartCount.textContent = count;
		cartCount.style.display = count > 0 ? 'flex' : 'none';
	})
	.catch(error => console.error(error));
}

function updateCartCount() {
	if (window.authUser) {
		updateLoggedUserCartCount();
	} else {
		updateLocalStorageCartCount();
	}
}

function updateFavoriteCount() {
	fetch('/favorite/count', {
		method: 'GET',
		headers: {
			'Content-Type': 'application/json',
			'Accept': 'application/json',
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
		}
	})
	.then(response => response.json())
	.then(data => {
		let favoriteCount = document.querySelector('.favorite-count');
		favoriteCount.textContent = data;
		favoriteCount.style.display = data > 0 ? 'flex' : 'none';
	})
	.catch(error => console.error(error));
}

if (window.authUser) {
	updateFavoriteCount();
}

// Toast Notification Function
function showToast(message, type = 'success') {
	const toastContainer = document.getElementById('toastContainer');
	const toast = document.createElement('div');
	toast.className = `toast ${type}`;
	toast.innerHTML = `
		<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
		<span>${message}</span>
	`;

	toastContainer.appendChild(toast);

	// Show toast
	setTimeout(() => toast.classList.add('show'), 100);

	// Hide after 3 seconds
	setTimeout(() => {
		toast.classList.remove('show');
		setTimeout(() => toast.remove(), 300);
	}, 3000);
}

let errorToasts = document.querySelectorAll('.errorToast');
let successToast = document.getElementById('successToast');

// Handle all error toasts
if (errorToasts.length > 0) {
	// Hide after 5 seconds
	errorToasts.forEach(errorToast => {
		if(errorToast.classList.contains('show')) {
			setTimeout(() => {
				errorToast.classList.remove('show');
				setTimeout(() => errorToast.remove(), 300);
			}, 5000);
		}
	});
}

if (successToast) {
	// Hide after 5 seconds
	setTimeout(() => {
		successToast.classList.remove('show');
		setTimeout(() => successToast.remove(), 300);
	}, 5000);
}

function getCartProducts() {
	if (window.authUser) {
		// User is logged in
		getLoggedUserCartProducts();
	} else {
		// Guest user
		getLocalStorageCartProducts();
	}
	updateCartCount();
}

function getLoggedUserCartProducts(){
	// User is logged in
	fetch('/' + locale + '/cart', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'Accept': 'application/json',
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
		},
		body: JSON.stringify({
			user_id: window.authUser.id,
		})
	})
	.then(response => response.json())
	.then(data => {
		handleCartDataView(data);

		if (window.location.pathname.split('/').pop() === 'checkout') {
			handleCheckoutDataView(data);
		}
	})
	.catch(error => console.error(error));
};

function getLocalStorageCartProducts(){
	// Guest user
	let cartData = localStorage.getItem('cart');

	if(cartData == null || cartData.length == 0 || cartData == '[]'){
		cartData = [];

		handleCartDataView({products: cartData, total: 0});
		updateCartCount();
		return;
	}

	fetch('/' + locale +'/cart', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'Accept': 'application/json',
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
		},
		body: JSON.stringify({
			products: JSON.parse(cartData),
		})
	})
	.then(response => response.json())
	.then(data => {
		handleCartDataView(data);

		if(data.removedSizes.length > 0) {
			data.removedSizes.forEach(size => {
				let cart = cartData || [];
				cart = cart.filter(item => !(item.product_id == size.product_id && item.size == size.size));
				localStorage.setItem('cart', JSON.stringify(cart));
				updateCartCount();
			});
		}
	})
	.catch(error => {
		const cartItems = document.querySelector('.cart-items');
		cartItems.innerHTML = '';
		const cartTotal = document.getElementById('cartTotal');
		cartTotal.textContent = '0 ' + window.translations.egp;
	});
};

function handleCheckoutDataView(data)
{
	const orderItems = document.querySelector('.order-items');
	orderItems.innerHTML = '';
	data.products.forEach(item => {
		const orderItem = document.createElement('div');
		orderItem.classList.add('order-item');
		orderItem.innerHTML = `
			<span>
				<strong>${item.product.name}</strong>
				<p dir="auto">${item.quantity} x ${item.size.size} ${window.translations.ml}</p>
			</span>
			<span>${(item.size.priceAfterDiscount * item.quantity).toFixed(2)} ${window.translations.egp}</span>
		`;
		orderItems.appendChild(orderItem);
	});

	const subtotal = document.getElementById('subtotal');
	subtotal.textContent = data.total + ' ' + window.translations.egp;
	subtotal.dataset.value = data.totalAsNumber;
	const shipping = document.getElementById('shipping');
	if(shipping.dataset.value && data.shippingCost != shipping.dataset.value){
		data.shippingCost = shipping.dataset.value;
	}else{
		shipping.textContent = data.shippingCost + ' ' + window.translations.egp;
		shipping.dataset.value = data.shippingCost;
	}
	let discountElement = document.getElementById('discount-amount-value').dataset.value;
	const total = document.getElementById('total');
	total.textContent = formatNumber(parseFloat(data.totalAsNumber) + parseFloat(data.shippingCost) - parseFloat(discountElement)) + ' ' + window.translations.egp;
}

function formatNumber(num) {
	return new Intl.NumberFormat('en-US', {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2
	}).format(num);
}

function handleCartDataView(data)
{
	const cartItems = document.querySelector('.cart-items');
			cartItems.innerHTML = '';
			data.products.forEach(item => {
				const cartItem = document.createElement('div');
				cartItem.classList.add('cart-item');
				cartItem.innerHTML = `
					<div class="cart-item">
			<div class="cart-item-img">
			<a href="/${locale}/product/${item.product.id}/size/${item.size.size}">
				<img src="${item.product.main_image}" alt="${item.product.name}">
			</a>
			</div>
			<div class="cart-item-details">
			<a href="/${locale}/product/${item.product.id}/size/${item.size.size}">
				<div class="cart-item-title">${item.product.name}</div>
				</a>
				<div class="cart-item-size">${item.size.size} ${window.translations.ml}</div>
				<div class="cart-item-price-container">
				<div class="cart-item-price">${formatNumber(item.size.priceAfterDiscount)} ${window.translations.egp}</div>
				${item.size.priceAfterDiscount != item.size.price ? `<div class="cart-item-priceAfterDiscount">${formatNumber(item.size.price)} ${window.translations.egp}</div>` : ''}
				</div>
				<div class="cart-item-quantity">
					<div class="quantity-btn minus" data-id="${item.product.id}" data-size="${item.size.size}" onclick="updateCartQuantity(this)">-</div>
					<div class="quantity-input">${item.quantity}</div>
					<div class="quantity-btn plus" data-id="${item.product.id}" data-size="${item.size.size}" onclick="updateCartQuantity(this)">+</div>
				</div>
				<div class="cart-item-remove" data-id="${item.product.id}" data-size="${item.size.size}" onclick="removeFromCart(this)">${window.translations.remove}</div>
			</div>
		</div>
				`;
				cartItems.appendChild(cartItem);
			});
			const cartTotal = document.getElementById('cartTotal');
			cartTotal.textContent = data.total + " " + window.translations.egp;
			const checkoutBtn = document.querySelector('.checkout-btn');
			const cartTotalDiv = document.querySelector('.cart-total');
			const browseBtn = document.getElementById('browseBtn');
			if(data.products.length > 0){
				checkoutBtn.style.display = 'block';
				cartTotalDiv.style.display = 'flex';
				browseBtn.style.display = 'none';
			}else{
				checkoutBtn.style.display = 'none';
				cartTotalDiv.style.display = 'none';
				browseBtn.style.display = 'block';
			}
}

function updateCartQuantity(element) {
	if (window.authUser) {
		// User is logged in
		updateLoggedUserCartQuantity(element);
	} else {
		// Guest user
		updateLocalStorageCartQuantity(element);
	}
}

function updateLoggedUserCartQuantity(element) {
	const productId = element.getAttribute('data-id');
	const size = element.getAttribute('data-size');
	const action = element.classList.contains('plus') ? 'plus' : 'minus';

	fetch('/cart/update', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'Accept': 'application/json',
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
		},
		body: JSON.stringify({
			product_id: productId,
			size: size,
			action: action,
		})
	})
	.then(response => response.json())
	.then(data => {
		getCartProducts();
		showToast(window.translations.product_quantity_updated, 'success');
	})
	.catch(error => console.error(error));
}

function updateLocalStorageCartQuantity(element) {
	const productId = element.getAttribute('data-id');
	const size = element.getAttribute('data-size');
	const action = element.classList.contains('plus') ? 'plus' : 'minus';

	let cart = JSON.parse(localStorage.getItem('cart')) || [];
	if (action == 'plus') {
		let existingItem = cart.find(item => item.product_id == productId && item.size == size);
		existingItem.quantity += 1;
	} else {
		let existingItem = cart.find(item => item.product_id == productId && item.size == size);
		existingItem.quantity -= 1;
		if (existingItem.quantity <= 0) {
			cart = cart.filter(item => !(item.product_id == productId && item.size == size));
		}
	}
	localStorage.setItem('cart', JSON.stringify(cart));
	getCartProducts();
	showToast(window.translations.product_quantity_updated, 'success');
}

function removeFromCartLocalStorage(element) {
	const productId = element.getAttribute('data-id');
	const size = element.getAttribute('data-size');

	let cart = JSON.parse(localStorage.getItem('cart')) || [];
	cart = cart.filter(item => !(item.product_id == productId && item.size == size));
	localStorage.setItem('cart', JSON.stringify(cart));
	getCartProducts();
	showToast(window.translations.product_removed_from_cart, 'success');
}

function removeFromCartLoggedUser(element) {
	const productId = element.getAttribute('data-id');
	const size = element.getAttribute('data-size');

	fetch('/cart/remove', {
		method: 'DELETE',
		headers: {
			'Content-Type': 'application/json',
			'Accept': 'application/json',
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
		},
		body: JSON.stringify({
			product_id: productId,
			size: size,
		})
	})
	.then(response => response.json())
	.then(data => {
		getCartProducts();
		showToast(window.translations.product_removed_from_cart, 'success');
	})
	.catch(error => console.error(error));
}

function removeFromCart(element) {
	if (window.authUser) {
		// User is logged in
		removeFromCartLoggedUser(element);
	} else {
		// Guest user
		removeFromCartLocalStorage(element);
	}
}

function syncCart() {
	if (window.authUser) {
		let cart = JSON.parse(localStorage.getItem('cart')) || [];

		if(cart.length ==0){
			return;
		}

		// User is logged in
		fetch('/cart/sync', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'Accept': 'application/json',
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
			},
			body: JSON.stringify({
				products: cart,
			})
		})
		.then(response => response.json())
		.then(data => {
			getCartProducts();
			// clear localStorage cart
			localStorage.removeItem('cart');
		})
		.catch(error => console.error(error));
	}
}

syncCart();
