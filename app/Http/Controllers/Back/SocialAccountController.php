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
        $user = Socialite::driver($provider)->user();
        dd($user);
        if ($provider == "facebook") {
            $token = $user->token;
            AccessToken::create([
                'token' => $token,
                "type" => $provider,
                "user_id" => 1
            ]);
        }else{
            $token = $user->token;
            AccessToken::create([
                'token' => $token,
                'token' => $token,
                "type" => $provider,
                "user_id" => 1
            ]);
        }
        dd("done");
    }


}
