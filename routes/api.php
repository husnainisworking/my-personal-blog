<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\CommentController;

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post:slug}', [PostController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category:slug}', [CategoryController::class, 'show']);
Route::get('/categories/{category:slug}/posts', [CategoryController::class, 'posts']);
Route::get('/tags', [TagController::class, 'index']);//List all tags with post counts
Route::get('/tags/{tag:slug}', [TagController::class, 'show']); //Get a single tag
Route::get('/tags/{tag:slug}/posts' , [TagController::class, 'posts']); //Get posts with tag
Route::get('/posts/{post:slug}/comments', [CommentController::class, 'index']);