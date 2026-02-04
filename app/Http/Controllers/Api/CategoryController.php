<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;

class CategoryController extends Controller
{
    /**
     * Get all categories with their post counts
     * 
     * withCount('posts') adds a posts_count attribute to each category
     * without loading all the actual posts (efficient)
     */

    public function index()
    {
        $categories = Category::withCount(['posts' => function($query) {
                // Only count published posts
                $query->where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '<=' , now());
        }])
        ->orderBy('name')
        ->get();

        return response()->json($categories);
    }

    /**
     * Get a single category by slug
     * 
     * @param Category $category - Auto-injected via route model binding
     */
    public function show(Category $category) {
        // Add post count to category
        $category->loadCount(['posts' => function($query){
            $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
        }]);

        return response()->json($category);
    }

    /**
     * Get all posts in a category
     * 
     * @param Category $category - Auto-injected via route model binding
     */
    public function posts(Category $category)
    {
        $posts = Post::where('category_id', $category->id)
        ->published()
        ->with(['user', 'tags'])
        ->latest('published_at')
        ->get();

        return response()->json([
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
            ],
            'posts' => $posts->map(function ($post) {
                return [
                    'id' =>$post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'excerpt' => $post->excerpt,
                    'featured_image' => $post->featured_image,
                    'reading_time' => $post->reading_time,
                    'published_at' => $post->published_at,
                    'author' => [
                        'id' => $post->user?->id,
                        'name' => $post->user?->name,
                    ],
                    'tags' => $post->tags,
                ];
            }),
                'total' => $posts->count(),
        ]);
    }

}
