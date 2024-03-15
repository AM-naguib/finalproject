<?php

namespace App\Http\Controllers\Back;

use App\Models\AccessToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class SocialAccountController extends Controller
{
    public function index()
    {
        return view('back.dashboard/dashboard');
    }


    public function provider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $p = $provider;
        $user = Socialite::driver($p)->user();

        if ($provider == "facebook") {
            $token = $user->token;
            AccessToken::create([
                'token' => $token,
                "type" => $provider,
                "user_id" => 1
            ]);
        }else{
            $token = $user->token;
            $token_secret = $user->tokenSecret;
            dd($token, $token_secret);
            AccessToken::create([
                'token' => $token,
                'dsdsd' => $token,
                "type" => $provider,
                "user_id" => 1
            ]);
        }
        dd("done");
    }


}
