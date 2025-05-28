<?php

namespace App\Repositories;

use App\Interfaces\IClientRepo;
use App\Models\Client;
use App\Repositories\BaseRepository;

class ClientRepo extends BaseRepository implements IClientRepo
{
    public function __construct(Client $model)
    {
        parent::__construct($model);
    }
}
