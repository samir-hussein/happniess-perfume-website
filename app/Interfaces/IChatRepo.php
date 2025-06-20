<?php

namespace App\Interfaces;

interface IChatRepo extends IRepository
{
    public function getMyChatOrCreateNew();
    public function getMyChat();
}
