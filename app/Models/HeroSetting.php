<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSetting extends Model
{
    protected $fillable = [
        'image',
        'background_color',
        'image_size',
    ];

    public function getImageAttribute()
	{
		return env("PANEL_URL") . "/storage/" . $this->attributes['image'];
	}
}
