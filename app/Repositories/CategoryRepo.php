<?php

namespace App\Repositories;

use App\Models\Category;
use App\Interfaces\ICategoryRepo;

class CategoryRepo extends BaseRepository implements ICategoryRepo
{
    public function __construct(Category $category)
    {
        parent::__construct($category);
    }
}
