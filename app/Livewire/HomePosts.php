<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class HomePosts extends Component
{
    use WithPagination;

    public function render()
    {

        // Fetch published posts with relationships, paginated
        $posts = Post::with(['user', 'category', 'tags'])
        ->whereNotNull('published_at')
        ->where('published_at', '<=', now())
        ->latest('published_at')
        ->simplePaginate(10);

        return view('livewire.home-posts', compact('posts'));
    }
}
