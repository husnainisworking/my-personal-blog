<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the homepage with published posts.
     * 
     * URL: / (homepage)
     * Fetches published posts only
     * Loads relationship (user, category, tags) to avoid extra queries
     * Orders by newest published_at.
     * Paginates results (10 per page).
     */
    public function index() 
    {
        $posts = Post::published()
            ->with(['user', 'category', 'tags'])
            ->latest(['published_at'])
            ->paginate(10);

        return view('welcome', compact('posts'));

    }
}
