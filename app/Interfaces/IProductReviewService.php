<?php

namespace App\Interfaces;

interface IProductReviewService
{
    public function reviews(int $productId, int $page);

    public function createOrUpdateReview(int $productId, int $rating, string $comment);

    public function deleteReview(int $productId);
}
