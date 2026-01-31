<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(){

    $posts = \App\Models\Post::latest()->get();

    return response()->json($posts);
    }
}
