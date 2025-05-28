<?php

namespace App\Services;

use App\Interfaces\IClientRepo;
use App\Interfaces\IAuthService;
use Illuminate\Support\Facades\Auth;

class AuthService implements IAuthService
{
    public function __construct(private IClientRepo $clientRepo) {}

    public function login(array $data)
    {
        $credentials = [
            'email' => $data['email'],
            'password' => $data['password'],
        ];

        if (Auth::attempt($credentials, true)) {
            return true;
        }

        return false;
    }

    public function register(array $data)
    {
        return $this->clientRepo->create($data);
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('home', app()->getLocale())->with('success', 'You have been logged out');
    }
}
