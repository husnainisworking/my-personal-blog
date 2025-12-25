<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DbSearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        if ($q === '') {
            return response()->json([], 200);
        }

        // short cache(30s) to reduce load from repeated identical queries
        $key = 'search:db:'.sha1($q);

        $results = Cache::remember($key, 30, function () use ($q) {
            return Post::published()
                ->where(function ($qb) use ($q) {
                    $qb->where('title', 'like', "%{$q}%")
                        ->orWhere('content', 'like', "%{$q}%");

                })
                ->orderBy('published_at', 'desc')
                ->limit(10)
                ->get(['id', 'title', 'slug', 'excerpt']);
        });

        return response()->json($results);
    }
}
