<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Handle search requests for blog posts.
     *
     * Searches posts by title and content
     * user types "Laravel" in blog's search box.
     * browser hits /search?q=Laravel
     */
    public function index(Request $request): View
    {
        $query = $request->input('q');

        // Require minimum 3 characters
        if (!$query || strlen($query) < 3) {
            return view('search', [
                'posts' => collect([]), // Empty collection
                'query' => $query,
                'error' => 'Please enter at least 3 characters to search.'
            ]);
        }

        $perPage = config('pagination.search');

        $posts = Post::published()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                // %{$query}% means "anywhere in the string"
                // search posts by title/excerpt
                    ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->with(['user', 'category', 'tags'])
            ->latest('published_at')
            ->paginate($perPage);

            // Highlight search terms in titles and excerpts
            $posts->getCollection()->transform(function ($post) use ($query) {
                $post->highlighted_title = $this->highlightText($post->title, $query);
                $post->highlighted_excerpt = $this->highlightText($post->excerpt ?? '', $query);
                return $post;
            });

        return view('search', compact('posts', 'query'));
    }
        // Highlight matching search terms in text
            private function highlightText($text, $query)
            {
                if (!$query|| !$text) {
                    return $text;
                }

                // Escape special regex characters in query
                $pattern = preg_quote($query, '/');

                // Wrap matches in <mark> tags (case-insensitive)
                return preg_replace("/($pattern)/i", '<mark class="bg-yellow-200 px-1 rounded">$1</mark>', $text);
            }
}
