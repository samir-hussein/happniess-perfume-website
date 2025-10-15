<?php

namespace App\Handlers;

use App\Interfaces\IHeroSettingRepo;

class GetFirstHeroSettingHandler
{
    public function __construct(private IHeroSettingRepo $heroSettingRepo) {}

    public function __invoke()
    {
        return $this->heroSettingRepo->getFirst();
    }
}
