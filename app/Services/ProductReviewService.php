<?php

namespace App\Services;

use App\Interfaces\IProductReviewRepo;
use App\Interfaces\IProductReviewService;
use App\Models\FCMToken;
use App\Facades\Firebase;
use Illuminate\Support\Facades\Log;

class ProductReviewService implements IProductReviewService
{
    public function __construct(private IProductReviewRepo $productReviewRepo) {}

    public function reviews(int $productId, int $page)
    {
        return $this->productReviewRepo->reviews($productId, $page);
    }

    public function createOrUpdateReview(int $productId, int $rating, string $comment)
    {
        $this->productReviewRepo->createOrUpdateReview($productId, $rating, $comment);

        // send notification
        $payload = [
            'title' => "New Review by " . request()->user()->name,
            'message' => $comment,
            'url' => env("PANEL_URL") . "/products/" . $productId . "/reviews?search=" . request()->user()->email
        ];

        $tokens = FCMToken::get()->pluck('token')->toArray();

        try {
            Firebase::sendNotification($tokens, $payload);
        } catch (\Throwable $th) {
            Log::info($th->getMessage() . " : " . $th->getTraceAsString());
        }

        return true;
    }

    public function deleteReview(int $productId)
    {
        return $this->productReviewRepo->deleteReview($productId);
    }
}
