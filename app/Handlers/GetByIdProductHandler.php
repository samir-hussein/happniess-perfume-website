<?php

namespace App\Handlers;

use App\Interfaces\IProductRepo;

class GetByIdProductHandler
{
    public function __construct(private IProductRepo $productRepo) {}

    public function __invoke(int $id, int|null $size)
    {
        return $this->productRepo->find($id, $size);
    }
}
