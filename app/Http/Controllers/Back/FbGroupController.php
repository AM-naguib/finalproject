<?php

namespace App\Http\Controllers\Back;

use App\Models\FbGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class FbGroupController extends Controller
{
    public function index()
    {
        $groups = FbGroup::where("user_id", auth()->user()->id)->get();
        return view('back.dashboard.accounts.groups-show', compact("groups"));


    }
    public function getGroups()
    {
        FbGroup::where("user_id", auth()->user()->id)->delete();
        $accessToken = auth()->user()->accessTokens()->where("type","facebook")->first()->token;

        $url = "https://graph.facebook.com/v18.0/me?fields=groups&access_token=$accessToken";
        $response = $this->makeRequest($url);

        if (isset ($response["groups"]['paging']['next'])) {

            $this->storeGroups($response["groups"]['paging']['next']);
        }
        return to_route('admin.fbgroups.show');
    }

    public function storeGroups($url)
    {

        $response = $this->makeRequest($url);

        $groups = $response['data'];
        foreach ($groups as $group) {
            $fbgroup = new FbGroup();
            $fbgroup->name = $group['name'];
            $fbgroup->group_id = $group['id'];
            $fbgroup->user_id = auth()->user()->id;
            $fbgroup->save();
        }

        if (isset ($response['paging']['next'])) {
            $next = $response['paging']['next'];
            $this->storeGroups($next);

        }
        return $groups;
    }
    public function makeRequest($url)
    {

        $response = Http::get($url);
        return $response->json();
    }


}
