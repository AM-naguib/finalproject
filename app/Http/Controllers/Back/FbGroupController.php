<?php

namespace App\Http\Controllers\Back;

use App\Models\FbGroup;
use App\Models\History;
use App\Jobs\StoreGroups;
use App\Models\AccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
        $user_id= auth()->user()->id;
        FbGroup::where("user_id", $user_id)->delete();

        $accessToken = auth()->user()->accessTokens()->where("type", "facebook")->first()->token;
        StoreGroups::dispatch($user_id, $accessToken);
        return to_route('admin.fbgroups.show');
    }

    public function storeGroups($url)
    {
        $user_id= auth()->user()->id;
        $response = $this->makeRequest($url);

        $groups = $response['data'];
        foreach ($groups as $group) {
            $fbgroup = new FbGroup();
            $fbgroup->name = $group['name'];
            $fbgroup->group_id = $group['id'];
            $fbgroup->user_id = $user_id;
            $fbgroup->save();
        }

        if (isset ($response['paging']['next'])) {
            $next = $response['paging']['next'];
            $this->storeGroups($next);
        }
        Log::info("5alas store groups");


        return $groups;
    }

    public function saveGroups($groups)
    {
        $user_id= 1;

        foreach ($groups as $group) {
            $fbgroup = new FbGroup();
            $fbgroup->name = $group['name'];
            $fbgroup->group_id = $group['id'];
            $fbgroup->user_id = $user_id;
            $fbgroup->save();
        }
        Log::info("5alas savegroup");
    }
    public function makeRequest($url)
    {
        $response = Http::get($url);
        return $response->json();
    }

    public function groupsSendPost(Request $request)
    {
        $imagePath = null;
        $imageName = null;
        $request->validate([
            "content" => "required|string",
            "groups" => "required|array",
            "image" => "nullable|image|mimes:png,jpg,jpeg,webp"
        ]);
        if ($request->hasFile("image")) {
            $imagePath = $request->file("image")->getPathname();
            $imageName = $request->file("image")->getClientOriginalName();
        }


        $content = $request->content;
        $groups = $request->groups;
        $token = $this->getAccountToken();
        $posts = $this->makePost($token, $groups, $content, $imagePath, $imageName);
        $this->saveHistory($posts, $content);
        return redirect()->route("admin.history")->with("success", "Posts sent successfully");

    }

    public function getAccountToken()
    {
        $accessToken = AccessToken::where("user_id", auth()->user()->id)->where("type", "facebook")->first()->token;
        return $accessToken;
    }

    public function makePost($token, $groups, $content, $imagePath, $imageName)
    {
        $success = [];
        foreach ($groups as $group) {
            if (!empty ($imagePath)) {
                $postResponse = Http::attach(
                    'source',
                    file_get_contents($imagePath),
                    $imageName
                )->post("https://graph.facebook.com/{$group}/photos", [
                            'message' => $content,
                            'access_token' => $token,
                            'privacy' => json_encode(['value' => 'EVERYONE'])
                        ])->json();

            } else {
                $postResponse = Http::post("https://graph.facebook.com/{$group}/feed", [
                    'message' => $content,
                    'access_token' => $token,
                ])->json();
            }
            if (isset ($postResponse["id"])) {
                $success[] = $postResponse["id"];
            }
        }

        return $success;
    }


    public function saveHistory($posts, $content)
    {

        foreach ($posts as $post) {
            History::create([
                "user_id" => auth()->user()->id,
                "type" => "FaceBook Group",
                "content" => $content,
                "post_link" => "https://facebook.com/" . $post
            ]);
        }

    }

}
