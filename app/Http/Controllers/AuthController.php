<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\IAuthService;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function __construct(private IAuthService $authService) {}

    public function logout()
    {
        return $this->authService->logout();
    }

    public function register(RegisterRequest $request)
    {
        $this->authService->register($request->validated());
        return redirect()->route('login', app()->getLocale())->with('success', 'You have been registered successfully');
    }

    public function login(LoginRequest $request)
    {
        if ($this->authService->login($request->validated())) {
            return redirect()->intended(route('home', app()->getLocale()))->with('success', 'You have been logged in successfully');
        }
        return back()->withErrors([
            'email' => __('The provided credentials do not match our records.'),
        ])->onlyInput('email');
    }
}
