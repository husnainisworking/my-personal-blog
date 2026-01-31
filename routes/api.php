<?php

use App\Http\Controllers\Api\PostController;

Route::get('/posts', [PostController::class, 'index']);