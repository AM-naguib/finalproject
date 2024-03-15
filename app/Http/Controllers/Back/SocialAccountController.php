<?php

namespace App\Http\Controllers\Back;

use App\Models\AccessToken;
use App\Models\FBGROUP;
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


        $user = Socialite::driver("facebook")->user();
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

    public function fbGroups()
    {
        $accessToken = 'EAADROllKAewBOZC1jSDvaly4GvkCvW6rl4us57wCVQMrZCYJeilNlphrgTZASlBIti8QewPqevg0ImJtBmZBFIrm23sO5EDIp6Dv4RaOD0x4eiN303Or2ZARIqEyafGInjCBpD7cZAmU8TEFTTABT0LxItslZAj';
        $url = "https://graph.facebook.com/v18.0/me?fields=groups&access_token=$accessToken";
        $response = $this->makeRequest($url);
        $data = [];
        $data[] = $response["groups"]["data"];
        if ($response["groups"]['paging']['next']) {

            $this->getGroups($response["groups"]['paging']['next'], $data);
        }
        return view('back.dashboard.accounts.fb-groups', compact("groups", "next"));
    }
    public function getGroups($url, $data)
    {

        $response = $this->makeRequest($url);

        $groups = $response['data'];
        foreach ($groups as $group) {
            $fbgroup = new FBGROUP();
            $fbgroup->name = $group['name'];
            $fbgroup->group_id = $group['id'];
            $fbgroup->user_id = auth()->user()->id;
            $fbgroup->save();
        }

        if ($response['paging']['next'] != null) {
            $next = $response['paging']['next'];
            $this->getGroups($next, $data);

        }
        return $groups;
    }


    public function makeRequest($url)
    {

        $response = Http::get($url);
        return $response->json();
    }




}
