<?php

namespace App\Http\Controllers\Back;

use App\Models\FbPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function pagesAddPost(){

        $pages = FbPage::where("user_id",auth()->user()->id)->select("name","page_id")->get();
        return view("back.dashboard.posts.pages-add-post",compact("pages"));
    }
}
