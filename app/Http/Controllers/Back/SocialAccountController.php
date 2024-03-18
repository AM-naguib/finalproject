<?php

namespace App\Http\Controllers\Back;

use App\Models\AccessToken;
use App\Models\FbGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class SocialAccountController extends Controller
{
    public function index()
    {
        $accounts = AccessToken::where("user_id", auth()->user()->id)->get();
        return view('back.dashboard.accounts.social-accounts', compact("accounts"));
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
                "user_id" => auth()->user()->id
            ]);
        } else {
            $token = $user->token;
            $token_secret = $user->tokenSecret;
            AccessToken::create([
                'token' => $token,
                'token_secret' => $token_secret,
                "type" => $provider,
                "user_id" => 1
            ]);
        }
        dd("done");
    }





}
