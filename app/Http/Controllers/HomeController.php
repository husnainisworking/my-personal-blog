<?php

namespace App\Http\Controllers;

use App\Services\CacheService;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the homepage with published posts.
     */
    public function index(): View
    {
     
        return view('welcome');

    }
}
