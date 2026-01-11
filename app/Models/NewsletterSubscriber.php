<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsletterSubscriber extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'email',
        'name',
        'subscribed',
        'confirmation_token',
        'unsubscribe_token',
        'confirmed_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'deleted_at' => 'datetime',
        'subscribed' => 'boolean',
    ];

    public function scopeConfirmed($query)
    {
        return $query->whereNotNull('confirmed_at')
            ->where('subscribed', true);
    }

    public function scopePending($query)
    {
        return $query->whereNull('confirmed_at');
    }
}
