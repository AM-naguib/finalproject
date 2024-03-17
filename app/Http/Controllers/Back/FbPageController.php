<?php

namespace App\Http\Controllers\Back;

use App\Models\AccessToken;
use App\Models\FbPage;
use App\Models\History;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class FbPageController extends Controller
{

    public function index()
    {
        $pages = FbPage::where("user_id", auth()->user()->id)->get();
        return view('back.dashboard.accounts.pages-show', compact("pages"));
    }

    public function getPages()
    {
        FbPage::where("user_id", auth()->user()->id)->delete();
        $accessToken = AccessToken::where("user_id", auth()->user()->id)->first();
        $url = "https://graph.facebook.com/v12.0/me/accounts?access_token=$accessToken";
        $this->storePages($url);
        return to_route('admin.fbpages.show');

    }

    public function storePages($url)
    {
        $data = $this->makeRequest($url);
        dd($data);
        // $pages = $data['data'];

        foreach ($pages as $page) {
            $nPage = new FbPage();
            $nPage->name = $page["name"];
            $nPage->page_id = $page["id"];
            $nPage->user_id = auth()->user()->id;
            $nPage->access_token = $page["access_token"];
            $nPage->save();
        }
        if (isset ($data['paging']['next'])) {
            $next = $data['paging']['next'];
            $this->storePages($next);
        }
    }
    public function makeRequest($url)
    {

        $response = Http::get($url);
        return $response->json();
    }

    public function pagesSendPost(Request $request)
    {
        $request->validate([
            "content" => "required|string",
            "pages" => "required"
        ]);
        $accountToken = $this->getAccountToken();

        $pagesTokens = $this->getPagesToken($request->pages, $accountToken);

        $successPosts = $this->makePost($pagesTokens, $request->content);
        $this->saveHistory($successPosts,$request->content);

        return redirect()->route("admin.history")->with("success", "Posts sent successfully");

    }


    public function getAccountToken()
    {

        $accessToken = AccessToken::where("user_id", auth()->user()->id)->where("type", "facebook")->first();
        return $accessToken->token;
    }


    public function getPagesToken($ids, $token)
    {
        $pagesWithTokens = [];
        foreach ($ids as $id) {
            $url = "https://graph.facebook.com/v12.0/$id?fields=access_token&access_token=$token";
            $res = $this->makeRequest($url);
            $pagesWithTokens[$res["id"]] = $res['access_token'];
        }
        return $pagesWithTokens;
    }

    public function makePost($tokens, $message)
    {
        $errors = [];
        $success = [];
        foreach ($tokens as $id => $token) {
            $response = Http::post("https://graph.facebook.com/$id/feed", [
                'message' => $message,
                'access_token' => $token,
            ]);

            $success[] = $response->json()["id"];
        }
        return $success;
    }



    public function saveHistory($posts,$content){

        foreach ($posts as $post){
            History::create([
                "user_id" => auth()->user()->id,
                "type" => "FaceBook Page",
                "content" => $content,
                "post_link" => $post
            ]);
        }

    }

}
