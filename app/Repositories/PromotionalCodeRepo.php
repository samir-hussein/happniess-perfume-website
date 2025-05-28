<?php

namespace App\Repositories;

use App\Interfaces\IPromotionalCodeRepo;
use App\Models\PromotionalCode;

class PromotionalCodeRepo extends BaseRepository implements IPromotionalCodeRepo
{
    public function __construct(PromotionalCode $model)
    {
        parent::__construct($model);
    }

    public function getByCode(string $code)
    {
        return $this->model->where('code', $code)->first();
    }

    public function incrementUsage(string $code)
    {
        $this->model->where('code', $code)->increment('actual_usage');
    }
}
