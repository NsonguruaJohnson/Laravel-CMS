<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index() {

        {
            // $search = request()->query('search');
            // if ($search) {
            //     // dd(request()->query('search'));
            //     $post = Post::where('title', 'LIKE', "%{$search}%")->simplePaginate(1);
            // } else {
            //     $post = Post::simplePaginate(2);
            // }
        }


        return view('welcome', [
            'categories' => Category::all(),
            'tags' => Tag::all(),
            // 'posts' => $post
            'posts' => Post::searched()->simplePaginate(3)
        ]);
    }
}
