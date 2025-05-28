<?php

namespace App\Interfaces;

interface IProductService
{
	public function getAll(array $data);
	public function find(int $id, array $data);
	public function minmumPrice();
	public function maximumPrice();
	public function sizes();
	public function tags();
	public function relatedProducts(int $id);
}
