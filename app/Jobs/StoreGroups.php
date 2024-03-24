<?php

namespace App\Jobs;

use App\Models\FbGroup;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Controllers\Back\FbGroupController;

class StoreGroups implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $user_id;
    private $accessToken;
    private $FbGroupController;
    public function __construct($user_id, $accessToken)
    {
        $this->FbGroupController = new FbGroupController();
        $this->user_id = $user_id;
        $this->accessToken = $accessToken;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $url = "https://graph.facebook.com/v18.0/me?fields=groups&access_token=$this->accessToken";
        $response = $this->FbGroupController->makeRequest($url);


        $fisrtGroups = $response["groups"]["data"];
        $this->saveGroups($fisrtGroups);

        if (isset ($response["groups"]['paging']['next'])) {

            $this->storeGroups($response["groups"]['paging']['next']);
        }


    }


    public function storeGroups($url)
    {
        $response = $this->FbGroupController->makeRequest($url);

        $groups = $response['data'];
        $this->saveGroups($groups);
        if (isset ($response['paging']['next'])) {
            $this->storeGroups($response['paging']['next']);
        }


        return $groups;
    }
    public function saveGroups($groups)
    {
        foreach ($groups as $group) {
            $fbgroup = new FbGroup();
            $fbgroup->name = $group['name'];
            $fbgroup->group_id = $group['id'];
            $fbgroup->user_id = $this->user_id;
            $fbgroup->save();
        }
    }

}
