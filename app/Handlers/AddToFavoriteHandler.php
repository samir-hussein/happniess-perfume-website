<?php

namespace App\Handlers;

use App\Interfaces\IFavoriteRepo;

class AddToFavoriteHandler
{
    public function __construct(private IFavoriteRepo $favoriteRepo) {}

    public function __invoke(array $data, int $clientId)
    {
        $favorite = $this->favoriteRepo->getFavoriteByProductIdAndClientId($data["product_id"], $clientId);

        if ($favorite) {
            return $this->favoriteRepo->removeFromFavorite($data["product_id"], $clientId);
        }

        return $this->favoriteRepo->create([
            'product_id' => $data["product_id"],
            'client_id' => $clientId,
        ]);
    }
}
