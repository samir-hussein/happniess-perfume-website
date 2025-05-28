<?php

namespace App\Interfaces;

interface IFavoriteRepo extends IRepository
{
    public function getFavoriteByProductIdAndClientId(int $productId, int $clientId);
    public function removeFromFavorite(int $productId, int $clientId);
    public function getFavoritesByClientId(int $clientId);
    public function getFavoritesCount(int $clientId);
}
