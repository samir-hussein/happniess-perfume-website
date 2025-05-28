<?php

namespace App\Services;

use App\Interfaces\IProductRepo;
use App\Interfaces\IProductService;
use App\Interfaces\IProductSizeRepo;
use App\Handlers\GetAllProductHandler;
use App\Handlers\GetByIdProductHandler;

class ProductService implements IProductService
{
	public function __construct(
		private GetAllProductHandler $getAllProductHandler,
		private GetByIdProductHandler $getByIdProductHandler,
		private IProductSizeRepo $productSizeRepo,
		private IProductRepo $productRepo
	) {}

	public function getAll(array $data)
	{
		return $this->getAllProductHandler->__invoke($data["page"] ?? 1, $data["limit"] ?? 9, $data["search"] ?? null, $data["categories"] ?? null, $data["tags"] ?? null, $data["price"] ?? null, $data["sort"] ?? null, $data["size"] ?? null);
	}

	public function find(int $id, array $data)
	{
		$this->productRepo->newVisit($id);
		return $this->getByIdProductHandler->__invoke($id, $data["size"] ?? null);
	}

	public function minmumPrice()
	{
		return $this->productSizeRepo->minimumPrice();
	}

	public function maximumPrice()
	{
		return $this->productSizeRepo->maximumPrice();
	}

	public function tags()
	{
		return $this->productRepo->tags();
	}

	public function sizes()
	{
		return $this->productSizeRepo->sizes();
	}

	public function relatedProducts(int $id)
	{
		return $this->productRepo->relatedProducts($id);
	}
}
