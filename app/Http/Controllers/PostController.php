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
        $validated['slug'] = Str::slug($validated['title']);

        //Make unique slug
        $originalSlug = $validated['slug'];
        $count = 1;
        while(Post::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug. '-' . $count;
            $count++;
        }

        //set publish cate if status is published
        // if the post is marked "published", record the current timestamp.
        if($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        //save the post, insert new post into posts table, $post is now the saved Post object.
        $post = Post::create($validated);

        //attach tags, if tags were selected in the form, link them to the post in the pivot table
        //(post_tag) = pivot table
        // example: Post #5 gets linked to Tag# 2 and Tag# 3
        if($request->has('tags')){
            $post->tags()->attach($request->tags);
        }

        //Redirect with success message
        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully !');
        //sends user back to the posts list page, flashes a success message to show at the top.
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

        //update slug if title changed
        $validated['slug'] = Str::slug($validated['title']);

        //make slug unique
        $originalSlug = $validated['slug'];
        $count = 1;
        while (Post::where('slug', $validated['slug'])
            ->where('id', '!=', $post->id)
            ->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count;
            $count++;
        }
        //checks if another post already uses the same slug.
        // if yes, appends -1, -2, etc., until it finds a unique slug.
        // the where('id', '!=' , $post->id) ensures it does not conflict with itself.

        //set published date if status changed
        if ($validated['status'] === 'published' && $post->status !== 'published') {
            $validated['published_at'] = now();
        }
        //if the post was previously a draft but is now being published -> set the
        // current timestamp.

        //update the post
        $post->update($validated);
        //saves all new validated data (title, content, slug, status , etc., ) into the database.

        //sync tags
        //if tags were submitted ->sync() updates the pivot table so the post
        //has exactly those tags(add new ones, remove missing ones).
        //if no tags were submitted->detach() removes all tag links for this post.
        //this is going to be many-to-many laravel relationship handling
        //sync([...]) updates the pivot table (post_tag) so that only the given IDs are attached to this post.

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        } else {
            $post->tags()->detach();
        }

        // redirect with success message , sends the user back to the posts list page.
        // flashes a success message.
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
