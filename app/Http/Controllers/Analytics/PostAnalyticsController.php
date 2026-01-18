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
        'time_spent' => 'required|integer|min:0',
        'scroll_depth' => 'required|integer|min:0|max:100',
    ]);

    PostEngaged::dispatch(
        $post,
        auth()->user(),
        $validated
    );

    return response()->json(['success' => true]);
}
}
