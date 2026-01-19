<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Events\PostEngaged;

class PostAnalyticsController extends Controller
{
    public function track(Request $request, Post $post) {
    $validated = $request->validate([
        'time_spent' => 'required|numeric|min:0',
        'scroll_depth' => 'required|numeric|min:0|max:100',
    ]);

    // Cast to integers
    $validated['time_spent'] = (int) $validated['time_spent'];
    $validated['scroll_depth'] = (int) $validated['scroll_depth'];

    PostEngaged::dispatch(
        $post,
        auth()->user(),
        $validated
    );

    return response()->json(['success' => true]);
}
}
