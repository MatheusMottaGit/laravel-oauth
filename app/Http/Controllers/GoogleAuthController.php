<?php

namespace App\Http\Controllers;

use App\Models\User;
use Http;

class GoogleAuthController extends Controller
{
    public function redirectToGoogleAuthorization() {
        $authorizationParams = http_build_query([
            'client_id' => env('GOOGLE_CLIENT_ID'),
            'redirect_uri' => env('GOOGLE_REDIRECT_URL'),
            'response_type' => 'code',
            'scope' => 'openid email profile',
        ]);
        
        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $authorizationParams);
    }
    public function handleGoogleCallback() {
        $accessTokenResponse = Http::post('https://oauth2.googleapis.com/token', [
            'code' => request()->input('code'),
            'client_id' => env('GOOGLE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_CLIENT_SECRET'),
            'redirect_uri' => env('GOOGLE_REDIRECT_URL'),
            'grant_type'=> 'authorization_code'
        ]);

        // return response()->json($accessTokenResponse->json());

        $accessToken = $accessTokenResponse->json()['access_token'];

        $googleUser = Http::withToken($accessToken)->get('https://www.googleapis.com/oauth2/v1/userinfo');

        $userExists = User::where('email', $googleUser['email'])->first();

        if(!$userExists) {
            User::create([
                // 'id' => $googleUser['id'],
                'name' => $googleUser['name'],
                'email' => $googleUser['email']
            ]);
        }

        return response()->json(['message' => 'Saved in database!', 'user' => $googleUser->json()]);
    }
}
