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
                $imageUrl = $googleUser->getAvatar();
                $imagePath = storage_path('app/public/profile/' . $googleUser->getId() . '.jpg');

                if (!file_exists($imagePath)) {
                    file_put_contents($imagePath, file_get_contents($imageUrl));
                }

                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'image' => 'profile/' . $googleUser->getId() . '.jpg',
                    
                ]);
                $user->assignRole('user'); // Assign a default role

                Auth::login($user);

                return redirect()->route('home')->with('success', 'Welcome! You have successfully logged in with Google.');
            }

            else {
                // If the user exists, log them in
                Auth::login($user);

                return redirect()->route('home')->with('success', 'Welcome back! You have successfully logged in with Google.');
            }
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['error' => 'Failed to authenticate with Google.']);
        }
    }
}
