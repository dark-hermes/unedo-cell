<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())->first();
            if (!$user) {
                // If the user does not exist, create a new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'image' => $googleUser->getAvatar()
                ]);

                Auth::login($user);

                return redirect()->route('admin.dashboard.index')->with('success', 'Welcome! You have successfully logged in with Google.');
            }

            else {
                // If the user exists, log them in
                Auth::login($user);

                return redirect()->route('admin.dashboard.index')->with('success', 'Welcome back! You have successfully logged in with Google.');
            }
        } catch (\Exception $e) {
            // return redirect()->route('login')->withErrors(['error' => 'Failed to authenticate with Google.']);
            dd($e->getMessage());
        }
    }
}
