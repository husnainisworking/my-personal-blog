<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OAuthAccount extends Model
{
    protected $table = 'oauth_accounts';

    protected $fillable = [
        'user_id',
        'provider',
        'provider_user_id',
        'email',
        'name',
        'access_token',
        'refresh_token',
        'id_token',
        'token_type',
        'scopes',
        'expires_at',
        'last_used_at',
    ];

    protected $casts = [
        // These casts encrypt/decrypt automatically when saving/reading
        'access_token' => 'encrypted',
        'refresh_token' => 'encrypted',
        'id_token' => 'encrypted',

        'expires_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
