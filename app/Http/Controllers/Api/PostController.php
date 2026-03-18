<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function index(){

    $postsQuery = Post::latest();

    $user = request()->user();
    if (!$user || !$user->hasPremiumAccess()) {
        $postsQuery->where('is_premium', false);
    }

    $posts = $postsQuery->get();

    return response()->json($posts);
    }

    /*
    * Get a single post by slug
    * @param \App\Models\Post $post - Automatically injected via route model binding
    * return \Illuminate\Http\JsonResponse
    */

    public function show(Post $post) {
        // Check if post is published (don't expose drafts via API)
        if (!$post->isPublished()) {
            return response()->json([
                'error' => 'Post not found'
            ], 404);
        }

        $user = request()->user();
        if ($post->is_premium && (!$user || !$user->hasPremiumAccess())) {
            return response()->json([
                'error' => 'Premium content. Upgrade required.'
            ], 403);
        }

        // Load related data (eager loading prevents N+1 queries)
        $post->load(['category', 'user', 'tags', 'approvedComments']);

        // Return post with additional computed fields
        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt,
            'content' => $post->content,
            'featured_image' => $post->featured_image,
            'views' => $post->views,
            'reading_time' => $post->reading_time,
            'published_at' => $post->published_at,
            'category' => $post->category,
            'author' => [
                'id' => $post->user->id,
                'name' => $post->user->name,
            ],
            'tags' => $post->tags,
            'comments_count' => $post->approvedComments->count(),
            'related_posts' => $post->getRelatedPosts(3),
        ]);
    }

}
