<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect() {
        return Socialite::driver('google')->redirect();
    }

    public function callback() {
        $googleUser = Socialite::driver('google')->user();

        $userExists = User::where('email', $googleUser->email)->first();

        if (!$userExists) {
            $user = User::updateOrCreate([
                'google_id' => $googleUser->id,
                'name' => $googleUser->name,
                'email' => $googleUser->email
            ]);

            Auth::login($user);
        }else{
            Auth::login($userExists);
        }
    }
}
