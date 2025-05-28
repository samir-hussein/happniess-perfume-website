<?php

namespace App\Services;

use App\Interfaces\ICategoryService;
use App\Handlers\GetAllCategoryHandler;

class CategoryService implements ICategoryService
{
    public function __construct(
        private GetAllCategoryHandler $getAllCategoryHandler,
    ) {}

    public function getAll()
    {
        return $this->getAllCategoryHandler->__invoke();
    }
}
