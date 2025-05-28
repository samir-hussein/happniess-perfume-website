<?php

namespace App\Repositories;

use App\Models\Favorite;
use App\Interfaces\IFavoriteRepo;

class FavoriteRepo extends BaseRepository implements IFavoriteRepo
{
    public function __construct(Favorite $model)
    {
        parent::__construct($model);
    }

    public function getFavoriteByProductIdAndClientId(int $productId, int $clientId)
    {
        return $this->model->where('product_id', $productId)->where('client_id', $clientId)->first();
    }

    public function removeFromFavorite(int $productId, int $clientId)
    {
        return $this->model->where('product_id', $productId)->where('client_id', $clientId)->delete();
    }

    public function getFavoritesByClientId(int $clientId)
    {
        return $this->model->where('client_id', $clientId)->pluck('product_id')->toArray();
    }

    public function getFavoritesCount(int $clientId)
    {
        return $this->model->where('client_id', $clientId)->count();
    }
}
