<?php

namespace App\Http\Controllers\Back;

use App\Models\FbPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class FbPageController extends Controller
{

public function index(){
    $pages = FbPage::where("user_id", auth()->user()->id)->get();
    return view('back.dashboard.accounts.pages-show', compact("pages"));
}

    public function getPages(){
        FbPage::where("user_id", auth()->user()->id)->delete();
        $accessToken = 'EAADROllKAewBOZCn3Y0oEZBFUHj50MMlEYXuiuZCriGyzrucwI1hZBs7evuk27Rg9GZBiG9tSJzAZCBfm2tTKZAZAnX32G4RZCj9LV0XgoL6gXDk6fQzVqPxBJZAzYmByIeQhfT2owIke4zzS4QE39lnuZCZCpfrVkGlA8hhv0ruvjud6GijudcZBWVtZAs4ALuF9UWufS8ypdfYW3A3FPv3laeYN52cnhfL7SIEmORdP7ovyaLkPQ9jtdaAzk';
        $url = "https://graph.facebook.com/v12.0/me/accounts?access_token=$accessToken";
        $this->storePages($url);
        return to_route('admin.fbpages.show');

    }

    public function storePages($url){
        $data = $this->makeRequest($url);
        $pages = $data['data'];
        foreach($pages as $page){
            $nPage = new FbPage();
            $nPage->name = $page["name"];
            $nPage->page_id = $page["id"];
            $nPage->user_id = auth()->user()->id;
            $nPage->access_token = $page["access_token"];
            $nPage->save();
        }
        if(isset($data['paging']['next'])){
            $next = $data['paging']['next'];
            $this->storePages($next);
        }
    }
    public function makeRequest($url)
    {

        $response = Http::get($url);
        return $response->json();
    }

}
