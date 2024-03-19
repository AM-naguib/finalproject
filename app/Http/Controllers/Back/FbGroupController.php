<?php

namespace App\Http\Controllers\Back;

use App\Models\FbGroup;
use App\Models\History;
use App\Models\AccessToken;
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

    public function groupsSendPost(Request $request){
        $content = $request->content;
        $groups = $request->groups;
        $token = $this->getAccountToken();
        $posts = $this->makePost($token,$groups,$content);
        $this->saveHistory($posts,$content);
        return redirect()->route("admin.history")->with("success", "Posts sent successfully");

    }

    public function getAccountToken(){
        $accessToken = AccessToken::where("user_id", auth()->user()->id)->where("type", "facebook")->first()->token;
        return $accessToken;
    }

    public function makePost($token,$groups,$content){
        $success = [];
        foreach($groups as $group){
            $res = Http::post("https://graph.facebook.com/$group/feed",[
                "message" => $content,
                "access_token" => $token
            ])->json();
            if(isset($res["id"])){
                $success[] = $res["id"];
            }
        }
        return $success;
    }

    public function saveHistory($posts,$content){

        foreach ($posts as $post){
            History::create([
                "user_id" => auth()->user()->id,
                "type" => "FaceBook Group",
                "content" => $content,
                "post_link" => "https://facebook.com/".$post
            ]);
        }

    }

}
