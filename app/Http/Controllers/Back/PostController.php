<?php

namespace App\Http\Controllers\Back;

use App\Models\AccessToken;
use App\Models\FbGroup;
use App\Models\FbPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function pagesAddPost(){

        $pages = FbPage::where("user_id",auth()->user()->id)->select("name","page_id")->get();
        return view("back.dashboard.posts.pages-add-post",compact("pages"));
    }

    public function groupsAddPost(){

        $groups = FbGroup::where("user_id",auth()->user()->id)->select("name","group_id")->get();
        return view("back.dashboard.posts.groups-add-post",compact("groups"));
    }

    public function twitterAddPost(){

        $accounts = AccessToken::where("user_id",auth()->user()->id)->where("type","twitter")->get();

        return view("back.dashboard.posts.twitter-add-post",compact("accounts"));
    }



}
