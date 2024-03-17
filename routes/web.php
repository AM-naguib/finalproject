<?php

use App\Models\FbPage;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\PostsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialiseController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Back\FbPageController;
use App\Http\Controllers\Back\FbGroupController;
use App\Http\Controllers\Back\HistoryController;
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
Route::prefix('dashboard')->name('admin.')->group(function () {
    Route::get("/", [DashboardController::class, 'index'])->name('dashboard');
    Route::get("social-accounts", [SocialAccountController::class, 'index'])->name('social-accounts');
    // Facebook Group Routes
    Route::get("social-accounts/get-groups", [FbGroupController::class, 'getGroups'])->name('groups.get');
    Route::get("social-accounts/show-groups", [FbGroupController::class, 'index'])->name('fbgroups.show');
    // Facebook Page Routes
    Route::get("social-accounts/get-pages", [FbPageController::class, 'getPages'])->name('fbpages.get');
    Route::get("social-accounts/show-pages", [FbPageController::class, 'index'])->name('fbpages.show');
    Route::post("posts/pages-send-post", [FbPageController::class, "pagesSendPost"])->name("posts.pages-send-post");

    // Posts Routes
    Route::get("posts/pages-add-post", [PostsController::class, "pagesAddPost"])->name("posts.pages-add-post");


    // History Routes
    Route::get("history", [DashboardController::class, 'history'])->name('history');
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
