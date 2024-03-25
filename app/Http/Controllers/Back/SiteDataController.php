<?php

namespace App\Http\Controllers\Back;

use Goutte\Client;
use App\Models\SiteData;
use App\Models\UserSite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SiteDataController extends Controller
{


    public function getPosts(Request $request)
    {

        $sites = UserSite::all();
        foreach ($sites as $site) {
            if (isset ($site->post_title_selector) && isset ($site->post_link_selector) && isset ($site->site_link)) {
                $res = $this->makeRequest($site->site_link);

                $data = $this->getData($res, $site->post_title_selector, $site->post_link_selector);
                $this->saveInDb($data,$site->id);
            } else {
                abort(404);
            }
        }
    }


    public function makeRequest($url)
    {
        $client = new Client();
        $res = $client->request("GET", $url);
        return $res;
    }
    public function getData($res, $post_title_selector, $post_url_selector)
    {
        // dd($res, $title_selector, $url_selector);
        $result = [];
        $res->filter($post_title_selector)->each(function ($titleNode, $i) use ($res, $post_url_selector, &$result) {
            $urlNode = $res->filter($post_url_selector)->eq($i);
            $title = $titleNode->text();
            $url = $urlNode->attr("href");
            $result[] = ['title' => $title, 'url' => $url];
        });
        return $result;
    }

    function saveInDb($data,$site_id){
        foreach ($data as  $value) {
            $siteData = new SiteData();
            $siteData->user_id = auth()->user()->id;
            $siteData->post_title = $value['title'];
            $siteData->post_url = $value['url'];
            $siteData->status = "waiting";
            $siteData->site_id = $site_id;
            $siteData->save();
        }
    }

}
