<?php

namespace App\Interfaces;

interface IAuthService
{
    public function login(array $credentials);
    public function register(array $data);
    public function logout();
}
