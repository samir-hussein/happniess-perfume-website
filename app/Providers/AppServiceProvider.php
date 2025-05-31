<?php

namespace App\Providers;

use App\Interfaces\ICartRepo;
use App\Services\AuthService;
use App\Services\CartService;
use App\Interfaces\IOrderRepo;
use App\Repositories\CartRepo;
use App\Services\OrderService;
use App\Interfaces\IClientRepo;
use App\Interfaces\IRepository;
use App\Repositories\OrderRepo;
use App\Interfaces\IAuthService;
use App\Interfaces\ICartService;
use App\Interfaces\IProductRepo;
use App\Repositories\ClientRepo;
use App\Services\ProductService;
use App\Interfaces\ICategoryRepo;
use App\Interfaces\IFavoriteRepo;
use App\Interfaces\IOrderLogRepo;
use App\Interfaces\IOrderService;
use App\Repositories\ProductRepo;
use App\Services\CategoryService;
use App\Services\CheckoutService;
use App\Services\FavoriteService;
use App\Repositories\CategoryRepo;
use App\Repositories\FavoriteRepo;
use App\Repositories\OrderLogRepo;
use App\Interfaces\IOrderItemsRepo;
use App\Interfaces\IProductService;
use App\Interfaces\ICategoryService;
use App\Interfaces\ICheckoutService;
use App\Interfaces\IFavoriteService;
use App\Interfaces\IProductSizeRepo;
use App\Repositories\BaseRepository;
use App\Repositories\OrderItemsRepo;
use Illuminate\Support\Facades\View;
use App\Repositories\ProductSizeRepo;
use App\Interfaces\IShippingMethodRepo;
use App\Services\ShippingMethodService;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\IPromotionalCodeRepo;
use App\Repositories\ShippingMethodRepo;
use App\Repositories\PromotionalCodeRepo;
use App\Interfaces\IShippingMethodService;
use App\Interfaces\IFirebaseService;
use App\Services\FirebaseService;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		$this->app->bind(IRepository::class, BaseRepository::class);
		$this->app->bind(ICategoryRepo::class, CategoryRepo::class);
		$this->app->bind(IProductRepo::class, ProductRepo::class);
		$this->app->bind(ICategoryService::class, CategoryService::class);
		$this->app->bind(IProductService::class, ProductService::class);
		$this->app->bind(IProductSizeRepo::class, ProductSizeRepo::class);
		$this->app->bind(ICartService::class, CartService::class);
		$this->app->bind(ICartRepo::class, CartRepo::class);
		$this->app->bind(IAuthService::class, AuthService::class);
		$this->app->bind(IClientRepo::class, ClientRepo::class);
		$this->app->bind(IFavoriteRepo::class, FavoriteRepo::class);
		$this->app->bind(IFavoriteService::class, FavoriteService::class);
		$this->app->bind(IShippingMethodRepo::class, ShippingMethodRepo::class);
		$this->app->bind(IShippingMethodService::class, ShippingMethodService::class);
		$this->app->bind(ICheckoutService::class, CheckoutService::class);
		$this->app->bind(IPromotionalCodeRepo::class, PromotionalCodeRepo::class);
		$this->app->bind(IOrderRepo::class, OrderRepo::class);
		$this->app->bind(IOrderService::class, OrderService::class);
		$this->app->bind(IOrderLogRepo::class, OrderLogRepo::class);
		$this->app->bind(IOrderItemsRepo::class, OrderItemsRepo::class);
		$this->app->bind(IFirebaseService::class, FirebaseService::class);
		
		// Register Firebase Facade
		$this->app->singleton('firebase', function ($app) {
			return $app->make(IFirebaseService::class);
		});
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		View::composer('Includes.navbar', function ($view) {
			$view->with('categories', $this->app->make(ICategoryService::class)->getAll());
		});
	}
}
