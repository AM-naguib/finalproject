<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialiseController extends Controller
{
    public function provider($provider){
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider){
        $user = Socialite::driver($provider)->user();
    }
}
