<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\IProductService;
use App\Interfaces\ICategoryService;
use App\Interfaces\IFavoriteService;
use App\Interfaces\IHeroSettingService;

class HomePageController extends Controller
{
	public function __construct(private IProductService $productService, private ICategoryService $categoryService, private IFavoriteService $favoriteService, private IHeroSettingService $heroSettingService) {}

	public function index(Request $request)
	{
		$data = $request->all();
		$data["limit"] = 12;
		
		return view("welcome", [
			"products" => $this->productService->getAll($data),
			"categories" => $this->categoryService->getAll(),
			"minPrice" => $this->productService->minmumPrice(),
			"maxPrice" => $this->productService->maximumPrice(),
			"tags" => $this->productService->tags(),
			"sizes" => $this->productService->sizes(),
			"favorites" => request()->user() ? $this->favoriteService->getFavoritesByClientId(request()->user()->id) : [],
			"heroSetting" => $this->heroSettingService->getFirst(),
			"newProducts" => $this->productService->getRandomNewProducts(4),
			"bestSellerProducts" => $this->productService->getBestSellerProducts(4),
			"bestOffersProducts" => $this->productService->getBestOffersProducts(4),
		]);
	}

	public function products(Request $request)
	{
		$data = $request->all();
		$data["limit"] = 16;
		return view("products", [
			"products" => $this->productService->getAll($data),
			"categories" => $this->categoryService->getAll(),
			"minPrice" => $this->productService->minmumPrice(),
			"maxPrice" => $this->productService->maximumPrice(),
			"tags" => $this->productService->tags(),
			"sizes" => $this->productService->sizes(),
			"favorites" => request()->user() ? $this->favoriteService->getFavoritesByClientId(request()->user()->id) : [],
		]);
	}
}
