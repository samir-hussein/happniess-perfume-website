<?php

namespace App\Repositories;

use App\Models\Product;
use App\Interfaces\IProductRepo;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class ProductRepo extends BaseRepository implements IProductRepo
{
    public function __construct(Product $product)
    {
        parent::__construct($product);
    }

    public function search(int $page, int $limit, string|null $search, array|null $categories, array|null $tags, string|null $price, string|null $sort, string|null $size)
    {
        $query = $this->model->query();

        if ($search) {
            $query->where("name_" . app()->getLocale(), "like", "%{$search}%");
        }

        if ($categories && $categories !== []) {
            $query->whereIn("category_id", $categories);
        }

        if ($tags && $tags !== []) {
            $query->whereIn("tag_" . app()->getLocale(), $tags);
        }

        if ($size && $size !== "any" && $size !== "") {
            $query->whereHas("sizes", function ($q) use ($size) {
                $q->where("size", $size);
            });
        }

        // Apply price filter
        if ($price) {
            $query->whereHas("sizes", function ($q) use ($price, $size) {
                $q->where("price", "<=", $price);

                if ($size && $size !== "any" && $size !== "") {
                    $q->where("size", $size);
                }
            });
        }

        // Apply sorting based on the sort parameter
        if ($sort && $sort !== "") {
            switch ($sort) {
                case 'asc':
                    // Sort by minimum price considering size filter
                    if ($size && $size !== "any" && $size !== "") {
                        $query->select('products.*')
                            ->addSelect(DB::raw("(SELECT MIN(price) FROM product_sizes WHERE product_sizes.product_id = products.id AND product_sizes.size = {$size}) as min_price"))
                            ->orderBy('min_price', 'asc');
                    } else {
                        $query->select('products.*')
                            ->addSelect(DB::raw('(SELECT MIN(price) FROM product_sizes WHERE product_sizes.product_id = products.id) as min_price'))
                            ->orderBy('min_price', 'asc');
                    }
                    break;
                case 'desc':
                    // Sort by minimum price considering size filter
                    if ($size && $size !== "any" && $size !== "") {
                        $query->select('products.*')
                            ->addSelect(DB::raw("(SELECT MIN(price) FROM product_sizes WHERE product_sizes.product_id = products.id AND product_sizes.size = {$size}) as min_price"))
                            ->orderBy('min_price', 'desc');
                    } else {
                        $query->select('products.*')
                            ->addSelect(DB::raw('(SELECT MIN(price) FROM product_sizes WHERE product_sizes.product_id = products.id) as min_price'))
                            ->orderBy('min_price', 'desc');
                    }
                    break;
                default:
                    $query->latest("id");
                    break;
            }
        } else {
            $query->latest("id");
        }

        // Load the sizes relationship with appropriate ordering
        $sizesQuery = function ($q) use ($price, $size) {
            if ($price) {
                $q->where("price", "<=", $price);
            }
            if ($size && $size !== "any" && $size !== "") {
                $q->where("size", $size);
            }
            $q->orderByRaw('CASE WHEN quantity = 0 THEN 1 ELSE 0 END')
                ->orderBy('price', 'asc');
        };

        return $query->with(["sizes" => $sizesQuery])->paginate($limit, ['*'], 'page', $page);
    }

    public function pagination(int $page, int $limit, array $productIds = [])
    {
        $query = $this->model->query();

        if ($productIds && $productIds != []) {
            $query->whereIn("id", $productIds);
        }

        return $query->with(["sizes" => function ($q) {
            $q->orderByRaw('CASE WHEN quantity = 0 THEN 1 ELSE 0 END')
                ->orderBy('price', 'asc');
        }])->latest("id")->paginate($limit, ['*'], 'page', $page);
    }

    public function find(int $id, int|null $size)
    {
        $query = $this->model->query();

        $query->where("id", $id);

        if ($size && $size !== "any" && $size !== "") {
            $query->whereHas("sizes", function ($q) use ($size) {
                $q->where("size", $size);
            });
        } else {
            abort(404);
        }

        $product = $query->with([
            "category",
            "sizes",
            "reviews" => function ($q) use ($id) {
                $q->where("client_id", Auth::user()?->id);
            }
        ])->withCount("reviews")->withAvg("reviews", "rate")->first();

        if (!$product) {
            abort(404);
        }

        return $product;
    }

    public function tags()
    {
        return $this->model->select("tag_" . app()->getLocale())
            ->distinct()
            ->get()
            ->pluck("tag_" . app()->getLocale())
            ->toArray();
    }

    public function relatedProducts(int $id)
    {
        $currentProduct = $this->model->find($id);
        return $this->model->where("id", "!=", $id)->where("category_id", $currentProduct->category_id)->inRandomOrder()->with(["sizes" => function ($q) {
            $q->orderByRaw('CASE WHEN quantity = 0 THEN 1 ELSE 0 END')
                ->orderBy('price', 'asc');
        }])->limit(4)->get();
    }

    public function getProductsForCart(array $ids)
    {
        return $this->model->select("id", "discount_amount", "discount_type", "name_" . app()->getLocale() . " as name", "main_image")->whereIn("id", $ids)->with(["sizes"])->get();
    }

    public function newVisit(int $productId)
    {
        return $this->model->find($productId)?->increment("views");
    }

    public function getRandomNewProducts(int $limit)
    {
        return $this->model->where("tag_en", "new")
            ->inRandomOrder()
            ->with(["sizes" => function ($q) {
                $q->orderByRaw('CASE WHEN quantity = 0 THEN 1 ELSE 0 END')
                    ->orderBy('price', 'asc');
            }, "category"])
            ->limit($limit)
            ->get();
    }

    public function getBestSellerProducts(int $limit)
    {
        return $this->model->where("tag_en", "best seller")
            ->inRandomOrder()
            ->with(["sizes" => function ($q) {
                $q->orderByRaw('CASE WHEN quantity = 0 THEN 1 ELSE 0 END')
                    ->orderBy('price', 'asc');
            }, "category"])
            ->limit($limit)
            ->get();
    }
}
