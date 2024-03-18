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
        $content = $request->content;
        $accounts = $request->accounts;
        $tokens = $this->getTokens($accounts);
        $successPosts = $this->makePost($tokens, $content);
        $this->saveHistory($successPosts,$content);
        return redirect()->route("admin.history")->with("success","Posts Sent Successfully");
    }


    public function getTokens($accounts)
    {
        $tokens = AccessToken::where("user_id", auth()->user()->id)->where("type", "twitter")->whereIn("id", $accounts)->select("name","token", "token_secret")->get();
        return $tokens;
    }

    public function makePost($tokens, $message)
    {
        $consumerKey = env('TWITTER_CLIENT_ID');
        $consumerSecret = env('TWITTER_CLIENT_SECRET');
        $success = [];
        foreach($tokens as $token){
            $twitterAuth  = new TwitterOAuth($consumerKey, $consumerSecret, $token->token, $token->token_secret);
            $postParams = ['text' => "$message"];
            $response = $twitterAuth->post('tweets', $postParams);
            if(isset($response->data->id)){
                $success[] = ["post_id"=>$response->data->id,"user_name"=>$token->name];
            }
        }
        return $success;

    }

    public function saveHistory($successPosts,$content){
        foreach($successPosts as $post){
            History::create([
                "user_id" => auth()->user()->id,
                "type" => "Twitter",
                "content" => $content,
                "post_link" => "https://twitter.com/".$post["user_name"]."/status/".$post["post_id"]
            ]);
        }
    }


}
