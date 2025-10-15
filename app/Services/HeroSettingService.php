<?php

namespace App\Services;

use App\Interfaces\IHeroSettingService;
use App\Handlers\GetFirstHeroSettingHandler;

class HeroSettingService implements IHeroSettingService
{
    public function __construct(
        private GetFirstHeroSettingHandler $getFirstHeroSettingHandler,
    ) {}

    public function getFirst()
    {
        return $this->getFirstHeroSettingHandler->__invoke();
    }
}
