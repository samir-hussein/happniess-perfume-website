<?php

namespace App\Repositories;

use App\Interfaces\IProductSizeRepo;
use App\Models\ProductSize;
use App\Repositories\BaseRepository;

class ProductSizeRepo extends BaseRepository implements IProductSizeRepo
{
	public function __construct(ProductSize $productSize)
	{
		parent::__construct($productSize);
	}

	public function minimumPrice()
	{
		return $this->model->min("price");
	}

	public function maximumPrice()
	{
		return $this->model->max("price");
	}

	public function sizes()
	{
		return $this->model->select("size")->distinct()->orderBy("size", "asc")->get()->pluck("size")->toArray();
	}
}
