<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DraftAutosave extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'draft_key',
        'title',
        'excerpt',
        'content',
        'category_id',
        'status',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    /**
     * Get the user that owns the draft
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the post being edited (if any)
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the category 
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Delete old drafts (older than 7 days)
     */
    public static function deleteOldDrafts(): int
    {
        return static::where('created_at', '<', now()->subDays(7))->delete();
    }
}
