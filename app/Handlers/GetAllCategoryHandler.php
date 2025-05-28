<?php

namespace App\Handlers;

use App\Interfaces\ICategoryRepo;

class GetAllCategoryHandler
{
    public function __construct(private ICategoryRepo $categoryRepo) {}

    public function __invoke()
    {
        return $this->categoryRepo->getAll();
    }
}
