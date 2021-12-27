<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\Posts\UpdatePostRequest;
use App\Http\Requests\Posts\CreatePostsRequest;

class PostsController extends Controller
{
    public function __construct() {
        $this->middleware('verifyCategoriesCount')->only(['create', 'store',]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('posts.index', [
            'posts' => Post::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create', [
            'categories' => Category::all(),
            'tags' => Tag::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostsRequest $request)
    {
        {
            // Storage::disk('local')->put('example.txt', 'Contents is edited');
            // $contents = Storage::get('example.txt');
            // $contents = Storage::url('example.txt');
            // $size = Storage::url('example.txt');
            // $path = Storage::putFileAs('photos', new File($request->image), 'today.jpg');
            // return Storage::download('example.txt', 'alliswell.txt');
            // dd($path);
            // $name = $request->image->hashName();
            // $visibility = Storage::getVisibility('example.txt');
            // $files = Storage::files($app);

            // dd($files);
        }
        // dd($request->tags);

        // dd($request->image->store('posts'));
        /**
         * Upload image to storage
         * Create the post
         * Flash message
         * Redirect user
         */

        $image = $request->image->store('posts');

        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'image' => $image,
            'published_at' => $request->published_at,
            'category_id' => $request->category,
            'user_id' => auth()->user()->id, // auth()->id
        ]);

        if($request->has('tags')) {
            $post->tags()->attach($request->tags);
        }

        session()->flash('success', 'Post created successfully');

        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        // dd($post->tags->pluck('id')->toArray());
        return view('posts.create', [
            'post' => $post,
            'categories' => Category::all(),
            'tags' => Tag::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        {
            /**
             * Check if new image
             * Upload it
             * delete old image
             * Update attributes
             * flash message
             * redirect user
             *
             */
        }
        //TODO: update category_id
        $data = $request->only(['title', 'description', 'published_at', 'content']);
        // $data = $request->only(['title', 'description', 'published_at', 'content', 'category_id']); // my fix
        // dd($data);
        // dd($data);
        // Check if new image
        if($request->hasFile('image')) {
            // Upload it
            $image = $request->image->store('posts');
            // delete old image
            // Storage::delete($post->image);
            $post->deleteImage();
            // Update attributes
            $data['image'] = $image;
        }
        // dd($data);

        if($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        $post->update($data);

        session()->flash('success', 'Post Updated successfully');

        return redirect(route('posts.index'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::withTrashed()->where('id', $id)->first(); //firstOrFail - 404 page
        // $post = Post::withTrashed()->find($id);
        // dd($post);
        if($post->trashed()) {
            // Storage::delete($post->image);
            $post->deleteImage();
            $post->forceDelete();
            return back()->with('success', 'Post Deleted successfully');
        } else {
            $post->delete();
            return back()->with('success', 'Post thrashed successfully');
        }

    }

    /**
     * Display a list of all thrashed posts
     *
     * @return \Illuminate\Http\Response
     */
    public function thrashed() {
        $thrashed = Post::onlyTrashed()->get();

        return view('posts.index')->withPosts($thrashed);
    }

    public function restore($id) {
        $post = Post::withTrashed()->where('id', $id)->first(); //firstOrFail - 404 page

        $post->restore();
        session()->flash('success', 'Post restored successfully');

        return back();
    }
}
