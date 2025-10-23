<?php

namespace App\Handlers;

use App\Interfaces\IProductRepo;

class GetBestOffersProductsHandler
{
    public function __construct(private IProductRepo $productRepo) {}

    public function __invoke(int $limit = 4)
    {
        return $this->productRepo->getBestOffersProducts($limit);
    }
}
