<?php

namespace App\Interfaces;

interface IFavoriteService
{
	public function addToFavorite(array $data, int $clientId);
	public function getFavoritesByClientId(int $clientId);
	public function getFavoritesCount(int $clientId);
	public function getFavoritesProductsByClientId(int $clientId);
}
