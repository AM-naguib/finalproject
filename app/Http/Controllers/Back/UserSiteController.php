<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\FbPage;
use App\Models\UserSite;
use Illuminate\Http\Request;

class UserSiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sites = UserSite::all();
        return view("back.dashboard.sites.index", compact("sites"));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pages = FbPage::where("user_id", auth()->user()->id)->get();

        return view("back.dashboard.sites.create" ,compact("pages"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
        $data = $request->validate([
            'site_name' => "required",
            "site_link" => "required|url|unique:user_sites,site_link",
            "post_link_selector" => "required",
            "post_title_selector" => "required",
        ]);
        $data["user_id"] = auth()->user()->id;
        UserSite::create($data);
        return back()->with("success", "Site created successfully");

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserSite $site)
    {

        if($site->user_id != auth()->user()->id){
            abort(403);
        }
        return view("back.dashboard.sites.edit", compact("site"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserSite $site)
    {
        if($site->user_id != auth()->user()->id){
            abort(403);
        }
        $data = $request->validate([
            'site_name' => "required",
            "site_link" => "required|url|unique:user_sites,site_link,{$site->id}",
            "post_link_selector" => "required",
            "post_title_selector" => "required",
        ]);
        $site->update($data);
        return redirect()->route("admin.sites.index")->with("success", "Site updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserSite $site)
    {
        if($site->user_id != auth()->user()->id){
            abort(403);
        }
        $site->delete();
        return back()->with("success", "Site deleted successfully");

    }
}
