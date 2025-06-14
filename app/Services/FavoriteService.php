<?php

namespace App\Services;

use App\Handlers\AddToFavoriteHandler;
use App\Interfaces\IFavoriteRepo;
use App\Interfaces\IFavoriteService;
use App\Interfaces\IProductRepo;

class FavoriteService implements IFavoriteService
{
    public function __construct(private IFavoriteRepo $favoriteRepo, private AddToFavoriteHandler $addToFavoriteHandler, private IProductRepo $productRepo) {}

    public function addToFavorite(array $data, int $clientId)
    {
        return $this->addToFavoriteHandler->__invoke($data, $clientId);
    }

    public function getFavoritesByClientId(int $clientId)
    {
        return $this->favoriteRepo->getFavoritesByClientId($clientId);
    }

    public function getFavoritesCount(int $clientId)
    {
        return $this->favoriteRepo->getFavoritesCount($clientId);
    }

    public function getFavoritesProductsByClientId(int $clientId)
    {
        $productIds = $this->favoriteRepo->getFavoritesByClientId($clientId);
        if (count($productIds) == 0) {
            return collect();
        }
        return $this->productRepo->pagination(request("page", 1), request("limit", 12), $productIds);
    }
}
