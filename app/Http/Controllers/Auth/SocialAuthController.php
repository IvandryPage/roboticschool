<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        // Use Socialite if available, otherwise show an error message
        if (!class_exists(Socialite::class)) {
            return redirect()->route('login')->withErrors(['google' => 'Socialite belum terpasang. Jalankan `composer require laravel/socialite`.']);
        }

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        if (!class_exists(Socialite::class)) {
            return redirect()->route('login')->withErrors(['google' => 'Socialite belum terpasang.']);
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            Log::error('Google login error: '.$e->getMessage());
            return redirect()->route('login')->withErrors(['google' => 'Gagal login dengan Google.']);
        }

        $user = User::firstOrCreate([
            'email' => $googleUser->getEmail(),
        ], [
            'name' => $googleUser->getName() ?? Str::before($googleUser->getEmail(), '@'),
            'password' => Hash::make(Str::random(16)),
        ]);

        Auth::login($user, true);

        return redirect()->intended('/dashboard');
    }
}
