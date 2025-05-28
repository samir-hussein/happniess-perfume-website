<?php

namespace App\Interfaces;

interface IPromotionalCodeRepo extends IRepository
{
	public function getByCode(string $code);
	public function incrementUsage(string $code);
}
