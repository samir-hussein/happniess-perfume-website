<?php

namespace App\Interfaces;

interface IProductRepo extends IRepository
{
    public function search(int $page, int $limit, string|null $search, array|null $categories, array|null $tags, string|null $price, string|null $sort, string|null $size);
    public function pagination(int $page, int $limit, array $productIds = []);
    public function find(int $id, int|null $size);
    public function tags();
    public function relatedProducts(int $id);
    public function getProductsForCart(array $ids);
    public function newVisit(int $productId);
    public function getRandomNewProducts(int $limit);
}
