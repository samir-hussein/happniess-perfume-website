<?php

namespace App\Interfaces;

interface IProductSizeRepo extends IRepository
{
    public function minimumPrice();
    public function maximumPrice();
    public function sizes();
}
