<?php

namespace App\Handlers;

use App\Interfaces\IProductRepo;

class GetRandomNewProductsHandler
{
    public function __construct(private IProductRepo $productRepo) {}

    public function __invoke(int $limit = 4)
    {
        return $this->productRepo->getRandomNewProducts($limit);
    }
}
