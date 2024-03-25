<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Back\PostController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Back\FbPageController;
use App\Http\Controllers\Back\FbGroupController;
use App\Http\Controllers\Back\TwitterController;
use App\Http\Controllers\Back\SiteDataController;
use App\Http\Controllers\Back\UserSiteController;
use App\Http\Controllers\Back\DashboardController;
use App\Http\Controllers\Back\SocialAccountController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index']);

Route::get("test", function () {

});

// back routes
Route::middleware('auth')->prefix('dashboard')->name('admin.')->group(function () {
    Route::get("/", [DashboardController::class, 'index'])->name('dashboard');
    Route::get("pricing", [DashboardController::class, 'pricing'])->name('pricing');
    Route::get("social-accounts", [SocialAccountController::class, 'index'])->name('social-accounts');
    // Facebook Group Routes
    Route::get("social-accounts/get-groups", [FbGroupController::class, 'getGroups'])->name('groups.get');
    Route::get("social-accounts/show-groups", [FbGroupController::class, 'index'])->name('fbgroups.show');
    Route::post("posts/groups-send-post", [FbGroupController::class, "groupsSendPost"])->name("posts.groups-send-post");
    // Facebook Page Routes
    Route::get("social-accounts/get-pages", [FbPageController::class, 'getPages'])->name('fbpages.get');
    Route::get("social-accounts/show-pages", [FbPageController::class, 'index'])->name('fbpages.show');
    Route::post("posts/pages-send-post", [FbPageController::class, "pagesSendPost"])->name("posts.pages-send-post");
    // Twitter Routes
    Route::post("posts/twitter-send-post", [TwitterController::class, "twitterSendPost"])->name("posts.twitter-send-post");

    // Posts Routes
    Route::get("posts/pages-add-post", [PostController::class, "pagesAddPost"])->name("posts.pages-add-post");
    Route::get("posts/groups-add-post", [PostController::class, "groupsAddPost"])->name("posts.groups-add-post");
    Route::get("posts/twitter-add-post", [PostController::class, "twitterAddPost"])->name("posts.twitter-add-post");

    // History Routes
    Route::get("history", [DashboardController::class, 'history'])->name('history');

    // Plans Routes
    Route::resource("plans", PlanController::class);

    // pricing routes


    // Sites Routes
    Route::resource("sites", UserSiteController::class);

    // Scraping Routes
    Route::get("scraping", [SiteDataController::class, 'getPosts'])->name('scraping');



});

Route::get("get", function (Request $request) {
    $data = $request->all();
    if (isset($data['post_title']) && isset($data['post_url']) && isset($data['site_url'])) {

        $post_title_selector = $data['post_title'];
        $post_url_selector = $data['post_url'];
        $site_url = $data['site_url'];

        $client = new Goutte\Client();
        $res = $client->request("GET", $site_url);

        $result = [];

        $res->filter($post_title_selector)->each(function ($titleNode, $i) use ($res, $post_url_selector, &$result) {
            $urlNode = $res->filter($post_url_selector)->eq($i);
            $title = $titleNode->text();
            $url = $urlNode->attr("href");
            $result[] = ['title' => $title, 'url' => $url];
        });

        // Return the result as JSON
        return response()->json($result);
    }

    // Return an error response if required parameters are missing
    return response()->json(['error' => 'Missing parameters'], 400);
});



// socialise routes
Route::get("auth/{provider}", [SocialAccountController::class, 'provider']);
Route::get("auth/{provider}/callback", [SocialAccountController::class, 'callback']);


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
