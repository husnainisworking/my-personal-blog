<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;


class TagController extends Controller
{
    /**
     * Get all tags with their post counts
     * 
     * withCount() adds a posts_count attribute without loading all posts
     * The closure filters to only count published posts
     */
    public function index()
    {
        $tags = Tag::withCount(['posts' => function ($query) {
            // Only count published posts
            $query->where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now());
        }])
        ->orderBy('name')
        ->get();

        return response()->json($tags);
    }

    /**
     * Get a single tag by slug
     * 
     * @param Tag $tag - Auto-injected via route model binding
     * Laravel finds Tag where slug = URL parameter
     */
    public function show(Tag $tag)
    {

        //loadCount() is like withCount() but for already-loaded models
        $tag->loadCount(['posts' => function($query) {
            $query->where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now());
        }]);

        return response()->json($tag);
    }

    /**
     * Get all posts with a specific tag
     * 
     * @param Tag $tag - Auto injected via route model binding 
     * 
     */
    public function posts(Tag $tag)
    {
        //whereHas() filters posts that have this tag in the pivot table
        $posts = Post::whereHas('tags', function($query) use ($tag) {
            $query->where('tags.id', $tag->id);
        })
        ->published()
        ->with(['category', 'user', 'tags'])
        ->latest('published_at')
        ->get();

        return response()->json([
            'tag' => [
              'id' => $tag->id,
              'name' => $tag->name,
              'slug' => $tag->slug,
            ],
            'posts' => $posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'excerpt' => $post->excerpt,
                    'featured_image' => $post->featured_image,
                    'reading_time' => $post->reading_time,
                    'published_at' => $post->published_at,
                    'category' => $post->category,
                    'author' => [
                        'id' => $post->user->id,
                        'name' => $post->user->name,
                    ],
                    'tags' => $post->tags,
                ];
            }),
            'total' => $posts->count(),
        ]);
    }    
}
