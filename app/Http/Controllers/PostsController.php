<?php

namespace App\Http\Controllers;

use App\Models\FbPage;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function pagesAddPost(){

        $pages = FbPage::where("user_id",auth()->user()->id)->select("name","page_id")->get();
        return view("back.dashboard.posts.add-post",compact("pages"));
    }



}
