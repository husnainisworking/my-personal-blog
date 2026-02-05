<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Get all approved comments for a post
     * 
     * @param Post $post - Auto-injected via route model binding
     * Only return approved comments
     * Hides sensitive data: email. ip_address
     */
    public function index(Post $post)
    {
        //Check if post is published
        if(!$post->isPublished()) {
            return response()->json([
                'error' => 'Post not found'
            ],404);
        }
    

    // Get only approved comments, newest first
    $comments = $post->comments()
        ->approved()  // Scope from Comment model
        ->latest()
        ->get();

        return response()->json([
            'post' => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
            ],
            'comments' => $comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'name' => $comment->name,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at,
                    //Note: email and ip_address are hidden for privacy
                ];
            }),
            'total' => $comments->count(),
        ]);

   } 
}
