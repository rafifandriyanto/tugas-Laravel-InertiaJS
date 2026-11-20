<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LandingController extends Controller
{
    /**
     * Display the modern landing page.
     */
    public function index()
    {
        $posts = Post::latest()->take(3)->get()->map(function ($post) {
            return [
                'id'         => $post->id,
                'title'      => $post->title,
                'content'    => $post->content,
                'created_at' => $post->created_at ? $post->created_at->toISOString() : null,
            ];
        });

        $stats = [
            'total_posts'  => Post::count(),
            'total_users'  => User::count(),
            'last_updated' => Post::latest()->value('updated_at')?->diffForHumans() ?? 'Hari ini',
        ];

        return Inertia::render('Landing', [
            'recentPosts' => $posts,
            'stats'       => $stats,
        ]);
    }
}
