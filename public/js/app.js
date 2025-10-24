// Mobile Menu Toggle
const menuToggle = document.querySelector(".menu-toggle");
const navLinks = document.querySelector(".nav-links");
const dropdownBtns = document.querySelectorAll(".dropdown-btn");
const locale = document.documentElement.lang || "{{ app()->getLocale() }}";
const luxuryChat = document.querySelector(".luxury-chat");
const chatToggle = document.querySelector(".chat-toggle");
const closeChat = document.querySelector(".close-chat");
const sendBtn = document.querySelector(".send-btn");
const chatInput = document.querySelector(".chat-footer textarea");
const messageContainer = document.querySelector(".message-container");
const chatBody = document.querySelector(".chat-body");
let chatId = document.querySelector("#chat-id").value;
const pulseDot = document.querySelector(".pulse-dot");

menuToggle.addEventListener("click", () => {
	navLinks.classList.toggle("active");
	menuToggle.innerHTML = navLinks.classList.contains("active")
		? '<i class="fas fa-times"></i>'
		: '<i class="fas fa-bars"></i>';
});

dropdownBtns.forEach((btn) => {
	btn.addEventListener("click", () => {
		const dropdown = btn.parentElement;
		dropdown.classList.toggle("active");
	});
});

// Cart Panel Toggle
const cartIcon = document.getElementById("cart-icon");
const cartPanel = document.querySelector(".cart-panel");
const closeCart = document.querySelector(".close-cart");
const overlay = document.querySelector(".overlay");

cartIcon.addEventListener("click", () => {
	getCartProducts();
	cartPanel.classList.add("active");
	overlay.classList.add("active");
});

closeCart.addEventListener("click", () => {
	cartPanel.classList.remove("active");
	overlay.classList.remove("active");
});

overlay.addEventListener("click", () => {
	cartPanel.classList.remove("active");
	overlay.classList.remove("active");
});

// Favorite Toggle
const favButtons = document.querySelectorAll(".add-to-fav, .add-to-fav-overlay");

favButtons.forEach((button) => {
	// Mark button as having listener attached
	button.setAttribute("data-fav-listener-attached", "true");
	
	button.addEventListener("click", function () {
		if (!window.authUser) {
			window.location.href = "/" + locale + "/login";
			return;
		}
		this.classList.toggle("favorited");
		const icon = this.querySelector("i");

		if (this.classList.contains("favorited")) {
			icon.classList.remove("far");
			icon.classList.add("fas");
		} else {
			icon.classList.remove("fas");
			icon.classList.add("far");
		}

		addToFavorites(this);
	});
});

// Add to favorites function
function addToFavorites(button) {
	const productId = button.getAttribute("data-id");
	const size = button.getAttribute("data-size");

	fetch("/favorite/add", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
			Accept: "application/json",
			"X-CSRF-TOKEN": document
				.querySelector('meta[name="csrf-token"]')
				.getAttribute("content"),
		},
		body: JSON.stringify({
			product_id: productId,
			size: size,
		}),
	})
		.then((response) => {
			if (response.ok) {
				// check if page is favorite
				if (window.location.pathname.split("/").pop() === "favorite") {
					window.location.reload();
				}
				updateFavoriteCount();
			}
		})
		.catch((error) => console.error(error));
}

function addToCart(e) {
	if (window.authUser) {
		// User is logged in
		addToCartLoggedUser(e);
	} else {
		// Guest user
		addToCartLocalStorage(e);
	}	
	// Animate cart icon to draw attention
	animateCartIcon();
}

function addToCartLocalStorage(e) {
	let cart = JSON.parse(localStorage.getItem("cart")) || [];
	let productId = e.getAttribute("data-id");
	let size = e.getAttribute("data-size");

	const existingItem = cart.find(
		(item) => item.product_id == productId && item.size == size
	);
	if (existingItem) {
		existingItem.quantity += 1;
	} else {
		cart.push({ product_id: productId, size: size, quantity: 1 });
	}

	localStorage.setItem("cart", JSON.stringify(cart));
	updateCartCount();
}

function addToCartLoggedUser(e) {
	let productId = e.getAttribute("data-id");
	let size = e.getAttribute("data-size");

	fetch("/cart/add", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
			Accept: "application/json",
			"X-CSRF-TOKEN": document
				.querySelector('meta[name="csrf-token"]')
				.getAttribute("content"),
		},
		body: JSON.stringify({
			product_id: productId,
			size: size,
			quantity: 1,
		}),
	})
		.then((response) => response.json())
		.then((data) => {
			// update cart count
			updateCartCount();
		})
		.catch((error) => console.error(error));
}

updateCartCount();

// Animate cart icon to draw attention
function animateCartIcon() {
	const cartIcon = document.getElementById("cart-icon");
	if (cartIcon) {
		cartIcon.classList.add("cart-pulse");
		setTimeout(() => {
			cartIcon.classList.remove("cart-pulse");
		}, 1000);
	}
}

function updateLocalStorageCartCount() {
	let cart = JSON.parse(localStorage.getItem("cart")) || [];
	let cartCount = document.querySelector(".cart-count");
	cartCount.textContent = cart.reduce(
		(total, item) => total + item.quantity,
		0
	);
	cartCount.style.display = cart.length > 0 ? "flex" : "none";
	
	// Fetch full cart data to update floating widget with total
	if (cart.length > 0) {
		fetchCartDataForWidget();
	} else {
		updateFloatingCartWidget();
	}
}

function updateLoggedUserCartCount() {
	fetch("/cart/count", {
		method: "GET",
		headers: {
			"Content-Type": "application/json",
			Accept: "application/json",
			"X-CSRF-TOKEN": document
				.querySelector('meta[name="csrf-token"]')
				.getAttribute("content"),
		},
	})
		.then((response) => response.json())
		.then((data) => {
			let count = parseInt(data, 10) || 0;
			let cartCount = document.querySelector(".cart-count");
			cartCount.textContent = count;
			cartCount.style.display = count > 0 ? "flex" : "none";
			
			// Fetch full cart data to update floating widget with total
			if (count > 0) {
				fetchCartDataForWidget();
			} else {
				updateFloatingCartWidget();
			}
		})
		.catch((error) => console.error(error));
}

function updateCartCount() {
	if (window.authUser) {
		updateLoggedUserCartCount();
	} else {
		updateLocalStorageCartCount();
	}
}

function updateFavoriteCount() {
	fetch("/favorite/count", {
		method: "GET",
		headers: {
			"Content-Type": "application/json",
			Accept: "application/json",
			"X-CSRF-TOKEN": document
				.querySelector('meta[name="csrf-token"]')
				.getAttribute("content"),
		},
	})
		.then((response) => response.json())
		.then((data) => {
			let favoriteCount = document.querySelector(".favorite-count");
			favoriteCount.textContent = data;
			favoriteCount.style.display = data > 0 ? "flex" : "none";
		})
		.catch((error) => console.error(error));
}

if (window.authUser) {
	updateFavoriteCount();
}

// Toast Notification Function
function showToast(message, type = "success") {
	const toastContainer = document.getElementById("toastContainer");
	const toast = document.createElement("div");
	toast.className = `toast ${type}`;
	toast.innerHTML = `
		<i class="fas ${
			type === "success" ? "fa-check-circle" : "fa-exclamation-circle"
		}"></i>
		<span>${message}</span>
	`;

	toastContainer.appendChild(toast);

	// Show toast
	setTimeout(() => toast.classList.add("show"), 100);

	// Hide after 3 seconds
	setTimeout(() => {
		toast.classList.remove("show");
		setTimeout(() => toast.remove(), 300);
	}, 3000);
}

let errorToasts = document.querySelectorAll(".errorToast");
let successToast = document.getElementById("successToast");

// Handle all error toasts
if (errorToasts.length > 0) {
	// Hide after 5 seconds
	errorToasts.forEach((errorToast) => {
		if (errorToast.classList.contains("show")) {
			setTimeout(() => {
				errorToast.classList.remove("show");
				setTimeout(() => errorToast.remove(), 300);
			}, 5000);
		}
	});
}

if (successToast) {
	// Hide after 5 seconds
	setTimeout(() => {
		successToast.classList.remove("show");
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

function getLoggedUserCartProducts() {
	// User is logged in
	fetch("/" + locale + "/cart", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
			Accept: "application/json",
			"X-CSRF-TOKEN": document
				.querySelector('meta[name="csrf-token"]')
				.getAttribute("content"),
		},
		body: JSON.stringify({
			user_id: window.authUser.id,
		}),
	})
		.then((response) => response.json())
		.then((data) => {
			handleCartDataView(data);

			if (window.location.pathname.split("/").pop() === "checkout") {
				handleCheckoutDataView(data);
			}
		})
		.catch((error) => console.error(error));
}

function getLocalStorageCartProducts() {
	// Guest user
	let cartData = localStorage.getItem("cart");

	if (cartData == null || cartData.length == 0 || cartData == "[]") {
		cartData = [];

		handleCartDataView({ products: cartData, total: 0 });
		updateCartCount();
		return;
	}

	fetch("/" + locale + "/cart", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
			Accept: "application/json",
			"X-CSRF-TOKEN": document
				.querySelector('meta[name="csrf-token"]')
				.getAttribute("content"),
		},
		body: JSON.stringify({
			products: JSON.parse(cartData),
		}),
	})
		.then((response) => response.json())
		.then((data) => {
			handleCartDataView(data);

			if (data.removedSizes.length > 0) {
				data.removedSizes.forEach((size) => {
					let cart = cartData || [];
					cart = cart.filter(
						(item) =>
							!(
								item.product_id == size.product_id &&
								item.size == size.size
							)
					);
					localStorage.setItem("cart", JSON.stringify(cart));
					updateCartCount();
				});
			}
		})
		.catch((error) => {
			const cartItems = document.querySelector(".cart-items");
			cartItems.innerHTML = "";
			const cartTotal = document.getElementById("cartTotal");
			cartTotal.textContent = "0 " + window.translations.egp;
		});
}

function handleCheckoutDataView(data) {
	const orderItems = document.querySelector(".order-items");
	orderItems.innerHTML = "";
	data.products.forEach((item) => {
		const orderItem = document.createElement("div");
		orderItem.classList.add("order-item");
		orderItem.innerHTML = `
			<span>
				<strong>${item.product.name}</strong>
				<p dir="auto">${item.quantity} x ${item.size.size} ${window.translations.ml}</p>
			</span>
			<span>${(item.size.priceAfterDiscount * item.quantity).toFixed(2)} ${
			window.translations.egp
		}</span>
		`;
		orderItems.appendChild(orderItem);
	});

	const subtotal = document.getElementById("subtotal");
	subtotal.textContent = data.total + " " + window.translations.egp;
	subtotal.dataset.value = data.totalAsNumber;
	const shipping = document.getElementById("shipping");
	if (shipping.dataset.value && data.shippingCost != shipping.dataset.value) {
		data.shippingCost = shipping.dataset.value;
	} else {
		shipping.textContent =
			data.shippingCost + " " + window.translations.egp;
		shipping.dataset.value = data.shippingCost;
	}
	let discountElement = document.getElementById("discount-amount-value")
		.dataset.value;
	const total = document.getElementById("total");
	total.textContent =
		formatNumber(
			parseFloat(data.totalAsNumber) +
				parseFloat(data.shippingCost) -
				parseFloat(discountElement)
		) +
		" " +
		window.translations.egp;
}

function formatNumber(num) {
	return new Intl.NumberFormat("en-US", {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2,
	}).format(num);
}

// Fetch cart data specifically for floating widget
function fetchCartDataForWidget() {
	if (window.authUser) {
		// For logged-in users
		fetch("/" + locale + "/cart", {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
				Accept: "application/json",
				"X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
			},
			body: JSON.stringify({
				user_id: window.authUser.id,
			}),
		})
		.then((response) => response.json())
		.then((data) => {
			updateFloatingCartWidget(data);
		})
		.catch((error) => console.error(error));
	} else {
		// For guest users
		let cartData = localStorage.getItem("cart");
		if (cartData && cartData !== "[]" && cartData.length > 0) {
			fetch("/" + locale + "/cart", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Accept: "application/json",
					"X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
				},
				body: JSON.stringify({
					products: JSON.parse(cartData),
				}),
			})
			.then((response) => response.json())
			.then((data) => {
				updateFloatingCartWidget(data);
			})
			.catch((error) => console.error(error));
		}
	}
}

// Update Floating Cart Widget
function updateFloatingCartWidget(cartData = null) {
	const floatingWidget = document.getElementById("floatingCartWidget");
	const floatingCount = document.querySelector(".floating-cart-count");
	const floatingTotal = document.getElementById("floatingCartTotal");
	
	// Hide on checkout page
	const currentPath = window.location.pathname;
	if (currentPath.includes('/checkout') || currentPath.includes('/order-confirmation')) {
		floatingWidget.style.display = "none";
		return;
	}
	
	// If cart data is provided, use it directly
	if (cartData) {
		const totalItems = cartData.products.length > 0 ? 
			cartData.products.reduce((sum, item) => sum + item.quantity, 0) : 0;
		
		if (totalItems > 0) {
			floatingCount.textContent = totalItems;
			floatingTotal.textContent = cartData.total;
			floatingWidget.style.display = "block";
		} else {
			floatingWidget.style.display = "none";
		}
		return;
	}
	
	// If no cart data provided, hide the widget
	floatingWidget.style.display = "none";
}

function handleCartDataView(data) {
	const cartItems = document.querySelector(".cart-items");
	cartItems.innerHTML = "";
	data.products.forEach((item) => {
		const cartItem = document.createElement("div");
		cartItem.classList.add("cart-item");
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
				<div class="cart-item-price">${formatNumber(item.size.priceAfterDiscount)} ${
			window.translations.egp
		}</div>
				${
					item.size.priceAfterDiscount != item.size.price
						? `<div class="cart-item-priceAfterDiscount">${formatNumber(
							  item.size.price
						  )} ${window.translations.egp}</div>`
						: ""
				}
				</div>
				<div class="cart-item-quantity">
					<div class="quantity-btn minus" data-id="${item.product.id}" data-size="${
			item.size.size
		}" onclick="updateCartQuantity(this)">-</div>
					<div class="quantity-input">${item.quantity}</div>
					<div class="quantity-btn plus" data-id="${item.product.id}" data-size="${
			item.size.size
		}" onclick="updateCartQuantity(this)">+</div>
				</div>
				<div class="cart-item-remove" data-id="${item.product.id}" data-size="${
			item.size.size
		}" onclick="removeFromCart(this)">${window.translations.remove}</div>
			</div>
		</div>
				`;
		cartItems.appendChild(cartItem);
	});
	const cartTotal = document.getElementById("cartTotal");
	cartTotal.textContent = data.total + " " + window.translations.egp;
	const checkoutBtn = document.querySelector(".checkout-btn");
	const cartTotalDiv = document.querySelector(".cart-total");
	const browseBtn = document.getElementById("browseBtn");
	if (data.products.length > 0) {
		checkoutBtn.style.display = "block";
		cartTotalDiv.style.display = "flex";
		browseBtn.style.display = "none";
	} else {
		checkoutBtn.style.display = "none";
		cartTotalDiv.style.display = "none";
		browseBtn.style.display = "block";
	}
	
	// Update floating cart widget with cart data
	updateFloatingCartWidget(data);
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
	const productId = element.getAttribute("data-id");
	const size = element.getAttribute("data-size");
	const action = element.classList.contains("plus") ? "plus" : "minus";

	fetch("/cart/update", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
			Accept: "application/json",
			"X-CSRF-TOKEN": document
				.querySelector('meta[name="csrf-token"]')
				.getAttribute("content"),
		},
		body: JSON.stringify({
			product_id: productId,
			size: size,
			action: action,
		}),
	})
		.then((response) => response.json())
		.then((data) => {
			getCartProducts();
		})
		.catch((error) => console.error(error));
}

function updateLocalStorageCartQuantity(element) {
	const productId = element.getAttribute("data-id");
	const size = element.getAttribute("data-size");
	const action = element.classList.contains("plus") ? "plus" : "minus";

	let cart = JSON.parse(localStorage.getItem("cart")) || [];
	if (action == "plus") {
		let existingItem = cart.find(
			(item) => item.product_id == productId && item.size == size
		);
		existingItem.quantity += 1;
	} else {
		let existingItem = cart.find(
			(item) => item.product_id == productId && item.size == size
		);
		existingItem.quantity -= 1;
		if (existingItem.quantity <= 0) {
			cart = cart.filter(
				(item) => !(item.product_id == productId && item.size == size)
			);
		}
	}
	localStorage.setItem("cart", JSON.stringify(cart));
	getCartProducts();
}

function removeFromCartLocalStorage(element) {
	const productId = element.getAttribute("data-id");
	const size = element.getAttribute("data-size");

	let cart = JSON.parse(localStorage.getItem("cart")) || [];
	cart = cart.filter(
		(item) => !(item.product_id == productId && item.size == size)
	);
	localStorage.setItem("cart", JSON.stringify(cart));
	getCartProducts();
}

function removeFromCartLoggedUser(element) {
	const productId = element.getAttribute("data-id");
	const size = element.getAttribute("data-size");

	fetch("/cart/remove", {
		method: "DELETE",
		headers: {
			"Content-Type": "application/json",
			Accept: "application/json",
			"X-CSRF-TOKEN": document
				.querySelector('meta[name="csrf-token"]')
				.getAttribute("content"),
		},
		body: JSON.stringify({
			product_id: productId,
			size: size,
		}),
	})
		.then((response) => response.json())
		.then((data) => {
			getCartProducts();
		})
		.catch((error) => console.error(error));
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
		let cart = JSON.parse(localStorage.getItem("cart")) || [];

		if (cart.length == 0) {
			return;
		}

		// User is logged in
		fetch("/cart/sync", {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
				Accept: "application/json",
				"X-CSRF-TOKEN": document
					.querySelector('meta[name="csrf-token"]')
					.getAttribute("content"),
			},
			body: JSON.stringify({
				products: cart,
			}),
		})
			.then((response) => response.json())
			.then((data) => {
				getCartProducts();
				// clear localStorage cart
				localStorage.removeItem("cart");
			})
			.catch((error) => console.error(error));
	}
}

syncCart();

// Toggle chat
chatToggle.addEventListener("click", function () {
	// Scroll to bottom
	scrollToBottom();
	luxuryChat.classList.toggle("active");
	markAsRead();
});

closeChat.addEventListener("click", function () {
	luxuryChat.classList.remove("active");
	markAsRead();
});

// Close chat when clicking outside
document.addEventListener("click", function (event) {
	// Check if the chat is active and the click is outside the chat
	if (luxuryChat.classList.contains("active") &&
			!luxuryChat.contains(event.target) &&
			!chatToggle.contains(event.target)) {
		luxuryChat.classList.remove("active");
		markAsRead();
	}
});

// Auto-scroll to bottom function
function scrollToBottom() {
	chatBody.scrollTo({
		top: chatBody.scrollHeight,
		behavior: "smooth",
	});
}

// Send message
function newMessage(messageData) {
	const message = messageData.content;
	const sender = messageData.sender;
	const time = messageData.time;
	if (message) {
		// Create user message
		const userMsg = document.createElement("div");
		userMsg.className = sender == "client" ? "message user" : "message bot";

		const avatar = document.createElement("div");
		avatar.className = "avatar";
		avatar.textContent = sender == "client" ? "You" : "HP";
		userMsg.appendChild(avatar);

		const bubble = document.createElement("div");
		bubble.className = "bubble";

		const p = document.createElement("p");
		p.textContent = message;
		bubble.appendChild(p);

		const span = document.createElement("span");
		span.className = "time";
		span.textContent = time;
		bubble.appendChild(span);

		userMsg.appendChild(bubble);

		messageContainer.appendChild(userMsg);

		// Scroll to bottom
		scrollToBottom();
	}
}

// Event listeners
sendBtn.addEventListener("click", sendMessage);
chatInput.addEventListener("keypress", function (e) {
	if (e.key === "Enter" && !e.shiftKey) {
		e.preventDefault();
		sendMessage();
	}
});

// Auto-resize textarea as user types
chatInput.addEventListener("input", function() {
	this.style.height = "auto";
	this.style.height = (this.scrollHeight) + "px";
});

// Initialize textarea height
window.addEventListener("load", function() {
	chatInput.style.height = "auto";
	chatInput.style.height = (chatInput.scrollHeight) + "px";
});

function initPusher(chatId) {
	var pusher = new Pusher('9e64f9fc2ea7af5accd1', {
			cluster: 'ap1',
			authEndpoint: '/pusher/auth',
			auth: {
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
			},
			withCredentials: true
		}
	});

	var channel = pusher.subscribe('private-chat.' + chatId);

	channel.bind('message', function(data) {
		newMessage(data.message);
		if(data.message.sender == 'admin' && data.message.read == false){
			pulseDot.classList.remove("pulse-hide");
		}
	});
}

if (chatId) {
	initPusher(chatId);
}

function sendMessage() {
	let message = chatInput.value.trim();

	if (!message) {
		return;
	}

	// Clear input
	chatInput.value = "";

	fetch("/send-message", {
			method: "POST",
			credentials: "include",
			headers: {
				"Content-Type": "application/json",
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
				"Accept": "application/json"
			},
			body: JSON.stringify({
				message: message
			})
		})
		.then(response => response.json())
		.then(data => {
			if(!chatId) {
				chatId = data.chat_id;
				initPusher(chatId);
				newMessage(data.message);
			}
		})
		.catch(error => {
			console.error("Error:", error);
		});
}

function markAsRead() {
	fetch("/mark-as-read", {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
				"Accept": "application/json"
			}
		})
		.then(response => response.json())
		.then(data => {
			pulseDot.classList.add("pulse-hide");
		})
		.catch(error => {
			console.error("Error:", error);
		});
}

// Page Loader Functionality
const pageLoader = document.getElementById('pageLoader');

// Show loader function
function showPageLoader() {
	if (pageLoader) {
		pageLoader.classList.add('active');
		document.body.style.overflow = 'hidden';
	}
}

// Hide loader function
function hidePageLoader() {
	if (pageLoader) {
		pageLoader.classList.remove('active');
		document.body.style.overflow = '';
	}
}

// Hide loader on page load
window.addEventListener('load', function() {
	hidePageLoader();
});

// Show loader when navigating away from the page (this fires when page actually starts unloading)
window.addEventListener('beforeunload', function() {
	showPageLoader();
});

// Also listen to pagehide event for better mobile support
window.addEventListener('pagehide', function() {
	showPageLoader();
});

// Handle all navigation links in navbar
document.addEventListener('DOMContentLoaded', function() {
	// Get all navigation links
	const navLinks = document.querySelectorAll('.nav-links a, .navbar a, .nav-icons a');
	
	navLinks.forEach(link => {
		link.addEventListener('click', function(e) {
			// Check if it's not a dropdown toggle or external link
			const href = this.getAttribute('href');
			
			// Skip if it's a hash link, javascript:void, or external link
			if (href && 
				href !== '#' && 
				!href.startsWith('javascript:') && 
				!href.startsWith('tel:') && 
				!href.startsWith('mailto:') &&
				!this.hasAttribute('target')) {
				
				// Show loader
				showPageLoader();
				
				// If navigation fails, hide loader after longer timeout for slow connections
				setTimeout(() => {
					hidePageLoader();
				}, 15000);
			}
		});
	});
	
	// Handle product links (for product cards)
	const productLinks = document.querySelectorAll('a[href*="/product/"]');
	
	productLinks.forEach(link => {
		link.addEventListener('click', function(e) {
			const href = this.getAttribute('href');
			
			if (href && !this.hasAttribute('target')) {
				showPageLoader();
				
				// Fallback to hide loader after timeout
				setTimeout(() => {
					hidePageLoader();
				}, 15000);
			}
		});
	});
	
	// Handle category links
	const categoryLinks = document.querySelectorAll('a[href*="/products"]');
	
	categoryLinks.forEach(link => {
		link.addEventListener('click', function(e) {
			const href = this.getAttribute('href');
			
			if (href && !this.hasAttribute('target')) {
				showPageLoader();
				
				// Fallback to hide loader after timeout
				setTimeout(() => {
					hidePageLoader();
				}, 15000);
			}
		});
	});
	
	// Handle form submissions that navigate to new pages
	const forms = document.querySelectorAll('form[method="GET"]');
	
	forms.forEach(form => {
		form.addEventListener('submit', function(e) {
			showPageLoader();
			
			// Fallback to hide loader after timeout
			setTimeout(() => {
				hidePageLoader();
			}, 15000);
		});
	});
});

// Handle browser back/forward buttons
window.addEventListener('pageshow', function(event) {
	// If page is loaded from cache (back/forward button)
	if (event.persisted) {
		hidePageLoader();
	}
});

// Additional safety: hide loader if it's been showing for too long
setTimeout(() => {
	hidePageLoader();
}, 20000);

// Scroll to Top Bar Functionality
const scrollTopBar = document.getElementById('scrollTopBar');

if (scrollTopBar) {
	// Show/hide scroll top bar based on scroll position
	window.addEventListener('scroll', function() {
		if (window.pageYOffset > 500) {
			scrollTopBar.classList.add('show');
		} else {
			scrollTopBar.classList.remove('show');
		}
	});

	// Scroll to top when clicked
	scrollTopBar.addEventListener('click', function() {
		window.scrollTo({
			top: 0,
			behavior: 'smooth'
		});
	});
}


