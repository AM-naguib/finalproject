<?php

namespace App\Http\Controllers\Back;

use App\Models\AccessToken;
use App\Models\History;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterController extends Controller
{
    public function twitterSendPost(Request $request)
    {
        $imagePath = null;
        $request->validate([
            "content" => "required|string",
            "accounts" => "required|exists:access_tokens,id,user_id," . auth()->user()->id,
            "image" => "image|mimes:jpeg,png,jpg,gif,svg,webp"
        ]);
        $content = $request->content;
        $accounts = $request->accounts;
        if($request->hasFile("image")){
            $imagePath = $request->file("image")->getPathname();
        }
        $tokens = $this->getTokens($accounts);
        $successPosts = $this->makePost($tokens, $content,$imagePath);
        $this->saveHistory($successPosts, $content);
        return redirect()->route("admin.history")->with("success", "Posts Sent Successfully");
    }


    public function getTokens($accounts)
    {
        $tokens = AccessToken::where("user_id", auth()->user()->id)->where("type", "twitter")->whereIn("id", $accounts)->select("name", "token", "token_secret")->get();
        return $tokens;
    }

    public function makePost($tokens, $message, $imagePath)
    {
        $consumerKey = env('TWITTER_CLIENT_ID');
        $consumerSecret = env('TWITTER_CLIENT_SECRET');
        $success = [];
        foreach ($tokens as $token) {
            $twitterAuth = new TwitterOAuth($consumerKey, $consumerSecret, $token->token, $token->token_secret);

            if($imagePath == null) {
                $postParams = [
                    'text' => "$message"
                ];
            }else{
                $image =[];
                $twitterAuth->setApiVersion("1.1");
                $media = $twitterAuth->upload('media/upload', ['media' => $imagePath]);
                $image[] = $media->media_id_string;
                $twitterAuth->setApiVersion("2");
                $postParams = [
                    'text' => "$message",
                    'media' => ['media_ids' => $image]
                ];
            }


            $response = $twitterAuth->post('tweets', $postParams);
            if (isset ($response->data->id)) {
                $success[] = ["post_id" => $response->data->id, "user_name" => $token->name];
            }
        }
        return $success;

    }

    public function saveHistory($successPosts, $content)
    {
        foreach ($successPosts as $post) {
            History::create([
                "user_id" => auth()->user()->id,
                "type" => "Twitter",
                "content" => $content,
                "post_link" => "https://twitter.com/" . $post["user_name"] . "/status/" . $post["post_id"]
            ]);
        }
    }


}
