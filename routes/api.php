<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CategoryController;

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post:slug}', [PostController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category:slug}', [CategoryController::class, 'show']);
Route::get('/categories/{category:slug}/posts', [CategoryController::class, 'posts']);
