<?php

namespace App\Handlers;

use App\Interfaces\IProductRepo;

class GetAllProductHandler
{
    public function __construct(private IProductRepo $productRepo) {}

    public function __invoke(int $page = 1, int $limit = 10, string|null $search, string|null $categories, string|null $tags, string|null $price, string|null $sort, string|null $size)
    {
        return $search || $categories || $tags || $price || $sort || $size ? $this->productRepo->search($page, $limit, $search, $categories ? explode(",", $categories) : [], $tags ? explode(",", $tags) : [], $price, $sort, $size) : $this->productRepo->pagination($page, $limit);
    }
}
