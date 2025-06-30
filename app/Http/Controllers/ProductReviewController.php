<?php

namespace App\Http\Controllers;

use App\Interfaces\IProductReviewService;
use Illuminate\Http\Request;
use App\Http\Requests\ReviewRequest;

class ProductReviewController extends Controller
{
    public function __construct(private IProductReviewService $productReviewService) {}

    public function reviews(string $locale, int $productId, Request $request)
    {
        return response()->json([
            "reviews" => view("ajax-components.reviews", [
                "reviews" => $this->productReviewService->reviews($productId, $request->page ?? 1),
                "locale" => $locale,
            ])->render()
        ]);
    }

    public function createOrUpdateReview(string $locale, int $productId, ReviewRequest $request)
    {
        $this->productReviewService->createOrUpdateReview($productId, $request->rate, $request->comment);
        return redirect()->back()->with('success', __('Review added successfully'));
    }

    public function deleteReview(string $locale, int $productId)
    {
        $this->productReviewService->deleteReview($productId);
        return redirect()->back()->with('success', __('Review deleted successfully'));
    }
}
