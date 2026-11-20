<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->get();
        return Inertia::render('Post/Index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Post/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required',
            'content' => 'required',
        ]);

        $post = Post::create([
            'title'   => $request->title,
            'content' => $request->content,
        ]);

        if ($post) {
            return Redirect::route('posts.index')
                ->with('message', 'Data Berhasil Disimpan!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return Inertia::render('Post/Edit', [
            'post' => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'   => 'required',
            'content' => 'required',
        ]);

        $post = Post::findOrFail($id);
        $post->update([
            'title'   => $request->title,
            'content' => $request->content,
        ]);

        return Redirect::route('posts.index')
            ->with('message', 'Data Berhasil Diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return Redirect::route('posts.index')
            ->with('message', 'Data Berhasil Dihapus!');
    }
}