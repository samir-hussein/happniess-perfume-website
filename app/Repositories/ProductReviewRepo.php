<?php

namespace App\Repositories;

use App\Interfaces\IProductReviewRepo;
use App\Models\ProductReview;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class ProductReviewRepo extends BaseRepository implements IProductReviewRepo
{
	public function __construct(ProductReview $productReview)
	{
		parent::__construct($productReview);
	}

	public function reviews(int $productId, int $page)
	{
		return $this->model->where("product_id", $productId)->with(["client"])->orderBy("created_at", "desc")->paginate(3, ["*"], "page", $page);
	}

	public function createOrUpdateReview(int $productId, int $rating, string $comment)
	{
		return $this->model->updateOrCreate([
			"product_id" => $productId,
			"client_id" => Auth::user()->id,
		], [
			"rate" => $rating,
			"comment" => $comment,
		]);
	}

	public function deleteReview(int $productId)
	{
		return $this->model->where("product_id", $productId)->where("client_id", Auth::user()->id)->delete();
	}
}
