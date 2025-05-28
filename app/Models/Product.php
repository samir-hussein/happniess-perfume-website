<?php

namespace App\Models;

use App\Models\Category;
use App\Models\ProductSize;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable =
    [
        'name_en',
        'name_ar',
        'main_image',
        'image1',
        'image2',
        'image3',
        'description_en',
        'description_ar',
        'discount_amount',
        'discount_type',
        'tag_en',
        'tag_ar',
        'views',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    public function getMainImageAttribute()
    {
        return env("PANEL_URL") . "/storage/" . $this->attributes['main_image'];
    }

    public function getImage1Attribute()
    {
        if ($this->attributes['image1']) {
            return env("PANEL_URL") . "/storage/" . $this->attributes['image1'];
        }
        return null;
    }

    public function getImage2Attribute()
    {
        if ($this->attributes['image2']) {
            return env("PANEL_URL") . "/storage/" . $this->attributes['image2'];
        }
        return null;
    }

    public function getImage3Attribute()
    {
        if ($this->attributes['image3']) {
            return env("PANEL_URL") . "/storage/" . $this->attributes['image3'];
        }
        return null;
    }

    public function getImagesAttribute()
    {
        return [
            "image1" => $this->image1,
            "image2" => $this->image2,
            "image3" => $this->image3
        ];
    }

    public function getAllImagesAttribute()
    {
        return [
            "main_image" => $this->main_image,
            "image1" => $this->image1,
            "image2" => $this->image2,
            "image3" => $this->image3,
        ];
    }

    public function images()
    {
        return [
            $this->attributes['image1'],
            $this->attributes['image2'],
            $this->attributes['image3'],
            $this->attributes['main_image']
        ];
    }

    public function imagesWithoutMain()
    {
        return [
            $this->attributes['image1'],
            $this->attributes['image2'],
            $this->attributes['image3'],
        ];
    }

    public function getPriceAfterDiscountAttribute()
    {
        if ($this->discount_type === "percentage") {
            return $this->sizes->first()->price - ($this->sizes->first()->price * $this->discount_amount / 100);
        } else {
            return $this->sizes->first()->price - $this->discount_amount;
        }
    }

    public function getSizePriceAttribute()
    {
        $requestedSize = request()->size;

        $size = $this->sizes->where("size", (int)$requestedSize)->first();

        return $size->price;
    }

    public function getSizePriceAfterDiscountAttribute()
    {
        $sizePrice = $this->getSizePriceAttribute();
        if ($this->discount_type === "percentage") {
            return $sizePrice - ($sizePrice * $this->discount_amount / 100);
        } else {
            return $sizePrice - $this->discount_amount;
        }
    }
}
