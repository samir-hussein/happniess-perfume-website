<?php

namespace App\Services;

use App\Interfaces\IProductRepo;
use App\Interfaces\IProductService;
use App\Interfaces\IProductSizeRepo;
use App\Handlers\GetAllProductHandler;
use App\Handlers\GetByIdProductHandler;
use App\Handlers\GetRandomNewProductsHandler;
use App\Handlers\GetBestSellerProductsHandler;
use App\Handlers\GetBestOffersProductsHandler;

class ProductService implements IProductService
{
    public function __construct(
        private GetAllProductHandler $getAllProductHandler,
        private GetByIdProductHandler $getByIdProductHandler,
        private GetRandomNewProductsHandler $getRandomNewProductsHandler,
        private GetBestSellerProductsHandler $getBestSellerProductsHandler,
        private GetBestOffersProductsHandler $getBestOffersProductsHandler,
        private IProductSizeRepo $productSizeRepo,
        private IProductRepo $productRepo
    ) {}

    public function getAll(array $data)
    {
        return $this->getAllProductHandler->__invoke($data["page"] ?? 1, $data["limit"] ?? 9, $data["search"] ?? null, $data["categories"] ?? null, $data["tags"] ?? null, $data["price"] ?? null, $data["sort"] ?? null, $data["size"] ?? null, $data["hasOffers"] ?? null);
    }

    public function find(int $id, int|null $size)
    {
        $this->productRepo->newVisit($id);
        return $this->getByIdProductHandler->__invoke($id, $size);
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

    public function getRandomNewProducts(int $limit)
    {
        return $this->getRandomNewProductsHandler->__invoke($limit);
    }

    public function getBestSellerProducts(int $limit)
    {
        return $this->getBestSellerProductsHandler->__invoke($limit);
    }

    public function getBestOffersProducts(int $limit)
    {
        return $this->getBestOffersProductsHandler->__invoke($limit);
    }
}
