<?php

namespace App\Repositories;

use App\Interfaces\IShippingMethodRepo;
use App\Models\ShippingMethod;
use App\Repositories\BaseRepository;

class ShippingMethodRepo extends BaseRepository implements IShippingMethodRepo
{
	public function __construct(ShippingMethod $model)
	{
		parent::__construct($model);
	}

	public function getAll()
	{
		return $this->model->orderBy('cost', 'asc')->get();
	}
}
