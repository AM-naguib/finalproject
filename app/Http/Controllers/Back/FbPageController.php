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
        $accessToken = AccessToken::where("user_id", auth()->user()->id)->where("type", "facebook")->first();

        $url = "https://graph.facebook.com/v12.0/me/accounts?access_token=$accessToken->token";


        $this->storePages($url);
        return to_route('admin.fbpages.show');

    }

    public function storePages($url)
    {
        $data = $this->makeRequest($url);
        $pages = $data['data'];

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
            "pages" => "required",
            "image" => "nullable|image|mimes:png,jpg,jpeg,webp"
        ]);

        if ($request->hasFile("image")) {

            $imagePath = $request->file("image")->store("public");
            $imageName = $request->file("image")->getClientOriginalName();
        }

        $accountToken = $this->getAccountToken();

        $pagesTokens = $this->getPagesToken($request->pages, $accountToken);
        $successPosts = $this->makePost($pagesTokens, $request->content, $imagePath);
        $this->saveHistory($successPosts, $request->content);

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

    public function makePost($tokens, $message, $photoPath)
    {
        $errors = [];
        $success = [];
        foreach ($tokens as $id => $token) {
            // upload photo without posting it
            $photoUploadResponse = Http::post("https://graph.facebook.com/$id/photos", [
                'access_token' => $token,
                'url' => "https://code-solutions.site/USED-Gift-Card.png",
                'published' => false,
            ]);

            if ($photoUploadResponse->successful()) {
                $photoId = $photoUploadResponse->json()['id'];
                // Then, create a post with the uploaded photo
                $postResponse = Http::post("https://graph.facebook.com/$id/feed", [
                    'message' => $message,
                    'access_token' => $token,
                    'attached_media' => json_encode([['media_fbid' => $photoId]]),
                    'published' => true,
                ]);
                if ($postResponse->successful()) {
                    $success[] = $postResponse->json()["id"];
                } else {
                    $errors[] = "Failed to post photo for $id: " . $postResponse->body();
                }
            } else {
                $errors[] = "Failed to upload photo for $id: " . $photoUploadResponse->body();
            }
        }
        return ['success' => $success, 'errors' => $errors];
    }




    public function saveHistory($posts, $content)
    {

        foreach ($posts as $post) {
            History::create([
                "user_id" => auth()->user()->id,
                "type" => "FaceBook Page",
                "content" => $content,
                "post_link" => "https://facebook.com/" . $post
            ]);
        }

    }

}
