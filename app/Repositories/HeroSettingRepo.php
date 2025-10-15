<?php

namespace App\Repositories;

use App\Models\HeroSetting;
use App\Interfaces\IHeroSettingRepo;

class HeroSettingRepo extends BaseRepository implements IHeroSettingRepo
{
    public function __construct(HeroSetting $heroSetting)
    {
        parent::__construct($heroSetting);
    }

    public function getFirst()
    {
        return $this->model::first();
    }
}
