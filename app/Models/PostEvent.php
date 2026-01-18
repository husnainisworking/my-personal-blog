<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class PostEvent extends Model
{
    public $timestamps = false; // Only created_at

    protected $fillable = [
        'event_type',
        'post_id',
        'user_id',
        'ip_address',
        'user_agent',
        'referrer',
        'data',
        'created_at'
    ];

    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime'
    ];

    // Relationships
    public function post() { return $this->belongsTo(Post::class); }
    public function user() { return $this->belongsTo(User::class); }

    // Scopes for analytics
    public function scopeViewed($query) {
        return $query->where('event_type', 'viewed');
    }

    public function scopeEngaged($query) {
        return $query->where('event_type', 'engaged');
    }

    public function scopeToday($query) {
        return $query->whereDate('created_at', today());
    }

    public function scopeLastDays($query, $days = 7) {
        return $query->where('created_at', '>=' , now()->subDays($days));
    }
}
