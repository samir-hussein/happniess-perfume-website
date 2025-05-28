<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to Google’s OAuth page.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('home', ['locale' => app()->getLocale()])->with('error', 'Google authentication failed');
        }

        // Check if the user already exists in the database
        $existingUser = Client::where('email', $user->email)->first();

        if ($existingUser) {
            // Log the user in if they already exist
            Auth::login($existingUser, true);
        } else {
            // Otherwise, create a new user and log them in
            $newUser = Client::updateOrCreate([
                'email' => $user->email
            ], [
                'name' => $user->name,
                'password' => Str::random(16), // Set a random password
                'email_verified_at' => now()
            ]);
            Auth::login($newUser, true);
        }

        // Redirect the user to the dashboard or any other secure page
        return redirect()->intended(route('home', ['locale' => app()->getLocale()]))->with('success', 'Google authentication successful');
    }
}
