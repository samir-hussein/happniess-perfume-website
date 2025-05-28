<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\IFavoriteService;
use App\Http\Requests\AddToFavoriteRequest;

class FavoriteController extends Controller
{
    public function __construct(private IFavoriteService $favoriteService) {}

    public function addToFavorite(AddToFavoriteRequest $request)
    {
        return response()->json($this->favoriteService->addToFavorite($request->validated(), $request->user()->id));
    }

    public function getFavoritesCount(Request $request)
    {
        return response()->json($this->favoriteService->getFavoritesCount($request->user()->id));
    }

    public function index(Request $request)
    {
        return view('favorite', [
            'products' => $this->favoriteService->getFavoritesProductsByClientId($request->user()->id),
            "favorites" => request()->user() ? $this->favoriteService->getFavoritesByClientId(request()->user()->id) : [],
        ]);
    }
}
