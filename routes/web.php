<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Blog\PostController;
use App\Http\Controllers\CategoriesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/blog/posts/{post}', [PostController::class, 'show'])->name('blog.show');

Auth::routes();


Route::middleware(['auth',])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('categories', CategoriesController::class);
    Route::resource('tags', TagsController::class);
    Route::resource('posts', PostsController::class);
    Route::get('thrashed-posts', [PostsController::class, 'thrashed'])->name('thrashed-posts.index');
    Route::put('restore-post/{post}', [PostsController::class, 'restore'])->name('restore-posts');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('users/profile', [UsersController::class, 'edit'])->name('users.edit-profile');
    Route::put('users/profile', [UsersController::class, 'update'])->name('users.update-profile');
    Route::get('users', [UsersController::class, 'index'])->name('users.index');
    Route::post('users/{user}/make-admin', [UsersController::class, 'makeAdmin'])->name('users.make-admin');

});

