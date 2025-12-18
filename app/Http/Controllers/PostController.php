<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Services\SlugService;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Shows all posts (Admin)
     */
    public function index()
    {
        $this->authorize('viewAny', Post::class);

        //This method is a controller action that handles showing all posts
        // in your admin panel. index() is conventional name for "list all items".
        // Eager loading occurs here.

            $posts = Post::with(['user', 'category', 'tags'])
                ->latest() //orders posts by the newest first (usually by created_at)
                ->paginate(10); //splits results into pages of 10 posts each, laravel automatically handles page links (?page=2, etc.).

            return view('posts.index', compact('posts'));
    }

    /**
     * Show trashed posts (soft deleted)
     */
    public function trashed()
    {
        $this->authorize('viewAny', Post::class);
        //This method shows all soft-deleted posts in the admin panel.

        $posts = Post::onlyTrashed() 
           ->with(['user' , 'category', 'tags'])
           ->latest()
           ->paginate(10);
           
        return view ('posts.trashed', compact('posts'));
    }



    /**
     * Show create form
     */
    public function create()
    {
       
        // NEW: Check permission
        $this->authorize('create', Post::class);

        $categories = Category::all();
        $tags = Tag::all();

        return view('posts.create', compact('categories', 'tags'));
    }

    /**
     * Store new post
     */
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();

        //If validation fails -> Laravel automatically redirects back with errors.
        //Add the user who created the post, get the currently logged-in user's ID.
        $validated['user_id'] = Auth::id();
        //Generate a slug from the title, turns title into a URL-friendly string.

        //Use atomic slug generation with database transaction
        $slug = SlugService::generateWithRetry(
            $validated['title'].
            Post::class,
            null,
            function($generatedSlug) use (&$validated, $request) {
                $validated['slug'] = $generatedSlug;

                // Set published date if status is published
                if($validated['status'] === 'published') {
                    $validated['published_at'] = now();
                }

                // Create post within the transaction
                DB::transaction(function() use (&$validated, $request, &$post) {
                    $post = Post::create($validated);

                    // Attach tags if provided
                    if($request->has('tags')) {
                        $post->tags()->attach($request->tags);
                    }
                });
            }
        );

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully !');
    }



    /**
     * Public view of a single post
     */
    public function show(Post $post)
    {   //Post $post -> laravel automatically injects the post you want to show
        //(based on the route parameter, e.g. /posts/5).
        $post->load(['user', 'category', 'tags', 'approvedComments']);
        //eager loading
        return view('posts.show', compact('post'));
    }

    /**
     * Admin edit form
     */
    public function edit(Post $post)
    {
        // Authorization check
        $this->authorize('update', $post);

        $categories = Category::all();
        $tags = Tag::all();

        return view('posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update post
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        // Authorization is handled in StorePostRequest
        // Validation is handled in StorePostRequest

        $validated = $request->validated();

        // Use atomic slug generation for updates
        $validated['slug'] = SlugService::updateSlug(
            $post,
            $validated['title'],
            Post::class
        );

        //Set published date if status changed to published
        if ($validated['status'] === 'published' && $post->status !== 'published') {
            $validated['published_at'] = now();
        }

        // Update within transaction
        DB::transaction(function() use ($post, $validated, $request) {
            $post->update($validated);

            // Sync tags
            if ($request->has('tags')) {
                $post->tags()->sync($request->tags);
            } else {
                $post->tags()->detach();
            }

        });

        return redirect()->route('posts.index')
            ->with('success', 'Post updated successfully !');

    }


    /**
     * Soft delete post (move to trash)
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully !');
    }



    /** 
     * Restore soft-deleted post
     */
    public function restore($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id); //find the soft-deleted post by ID, findOrFail throws 404 if not found.

        $this->authorize('restore', $post); // it checks if the user has permission to restore this post.

        $post->restore(); //calls Eloquent's restore() method to un-delete the post.

        return redirect()->route('posts.index')
            ->with('success', 'Post restored successfully !');

    }

    /**
     * Permanently delete a soft-deleted post
    */
    public function forceDelete($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        /**
         * onlyTrashed() retrieves only soft-deleted posts, $id is the post's ID.
         */
        $this->authorize('forceDelete', $post);

        $post->forceDelete();

        return redirect()->route('posts.trashed')
            ->with('success', 'Post permanently deleted !');
    }

    /**
     * Generate a unique slug for the post
     */

    private function generateUniqueSlug (string $title, ?int $excludeId = null): string
    /**
     * int $excludeId means an optional post ID to exclude from the uniqueness check
     * string $title is the post title to generate the slug from.
     */
    {
        $slug = Str::slug($title); 
        // Generates a URL-friendly slug from the title.
        $originalSlug = $slug;
        $count = 1;

        $query = Post::where('slug',  $slug);
        // Builds a query to check for existing posts with the same slug.

        if($excludeId) {
            $query->where('id', '!=', $excludeId);
            /**
             * This line modifies the query to exclude a specific post ID from the uniqueness check.
             * This is useful when updating a post, so it doesn't conflict with its own slug.   
             */
        }

        while($query->exists()) {
            /**
             * The loop continues as long as a post with the current slug exists in the database.
             */
            $slug = $originalSlug . '-' . $count;
            $count++;

            $query = Post::where('slug', $slug);
            /**
             * Checks again if the new slug exists.
             */
            if($excludeId) {
                $query->where('id', '!=', $excludeId);
                // It again excludes the specified post ID if provided, here excludeId is id of the post being updated.
            }
        }

        return $slug;
        // Returns a unique slug.

    }









}
