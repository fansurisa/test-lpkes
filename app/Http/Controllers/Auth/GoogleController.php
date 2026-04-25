<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')->withErrors(['google' => 'Login dengan Google gagal. Silakan coba lagi.']);
        }

        $user = User::updateOrCreate(
            ['google_id' => $googleUser->getId()],
            [
                'name'               => $googleUser->getName(),
                'email'              => $googleUser->getEmail(),
                'avatar'             => $googleUser->getAvatar(),
                'email_verified_at'  => now(),
            ]
        );

        if (!$user->hasRole('admin') && !$user->hasRole('user')) {
            $user->assignRole('user');
        }

        Auth::login($user, remember: true);

        if (!$user->isProfileCompleted()) {
            return redirect()->route('profile.complete');
        }

        return redirect()->intended(route('dashboard'));
    }
}
