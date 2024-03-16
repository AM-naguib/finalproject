<?php

namespace App\Http\Controllers\Back;

use App\Models\FBGROUP;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class FbGroupController extends Controller
{
    public function index()
    {
        $groups = FBGROUP::where("user_id", auth()->user()->id)->get();
        return view('back.dashboard.accounts.groups-show', compact("groups"));


    }
    public function getGroups()
    {
        FBGROUP::where("user_id", auth()->user()->id)->delete();
        $accessToken = 'EAADROllKAewBOZCn3Y0oEZBFUHj50MMlEYXuiuZCriGyzrucwI1hZBs7evuk27Rg9GZBiG9tSJzAZCBfm2tTKZAZAnX32G4RZCj9LV0XgoL6gXDk6fQzVqPxBJZAzYmByIeQhfT2owIke4zzS4QE39lnuZCZCpfrVkGlA8hhv0ruvjud6GijudcZBWVtZAs4ALuF9UWufS8ypdfYW3A3FPv3laeYN52cnhfL7SIEmORdP7ovyaLkPQ9jtdaAzk';
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
            $fbgroup = new FBGROUP();
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



}
