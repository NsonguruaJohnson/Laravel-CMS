<?php

namespace App\Http\Controllers\Blog;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function show(Post $post) {
        return view('blog.show')->with('post', $post);
    }

    public function category(Category $category) {

        // $search = request()->query('search');

        // if ($search) {
        //     $posts = $category->posts()->where('title', 'LIKE', "%{$search}%")->simplePaginate(3);
        // } else {
        //     $posts = $category->posts()->simplePaginate(3);
        // }

        return view('blog.category', [
            'category' => $category,
            'categories' => Category::all(),
            'tags' => Tag::all(),
            'posts' => $category->posts()->searched()->simplePaginate(3)
        ]);
    }

    public function tag(Tag $tag) {
        return view('blog.tag', [
            'tag' => $tag,
            'posts' => $tag->posts()->searched()->simplePaginate(3),
            'tags' => Tag::all(),
            'categories' => Category::all()

        ]);
    }

}
