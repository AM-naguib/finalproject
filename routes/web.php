<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialiseController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Back\DashboardController;

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

Route::get('/',[HomeController::class, 'index']);



// back routes
Route::prefix('dashboard')->name('admin.')->group(function(){
    Route::get("/",[DashboardController::class, 'index']);
    Route::get("social-accounts",[DashboardController::class, 'index'])->name('social-accounts');
});


// socialise routes
Route::get("auth/{provider}",[SocialiseController::class, 'provider']);
Route::get("auth/{provider}/callback",[SocialiseController::class, 'callback']);


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
