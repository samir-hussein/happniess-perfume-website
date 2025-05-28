<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\IProductService;
use App\Interfaces\IFavoriteService;

class ProductPageController extends Controller
{
    public function __construct(private IProductService $productService, private IFavoriteService $favoriteService) {}

    public function index(Request $request, string $locale, int $id)
    {
        return view('product', [
            "product" => $this->productService->find($id, $request->all()),
            "relatedProducts" => $this->productService->relatedProducts($id),
            "favorites" => request()->user() ? $this->favoriteService->getFavoritesByClientId(request()->user()->id) : [],
        ]);
    }
}
