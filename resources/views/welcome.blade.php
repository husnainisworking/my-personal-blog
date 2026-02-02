@extends('layouts.public')
@section('title', 'Home')

@section('content')
    <!-- Homepage (Welcome Page) of blog. -->
    <div class="mb-12 text-center">
        <h1 class="text-3xl sm:text-5xl font-bold text-gray-900 dark:text-gray-100 mb-4">Welcome to My Personal Blog</h1>
        <p class="text-xl text-gray-600">Sharing thoughts, ideas, and stories</p>
    </div>

    <livewire:home-posts />
@endsection
