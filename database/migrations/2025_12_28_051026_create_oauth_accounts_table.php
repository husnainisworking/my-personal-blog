<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('oauth_accounts', function (Blueprint $table) {
            $table->id();

            // Links this OAuth identity to a local user
            $table->foreignId('user_id')->constrained()->cascadeonDelete();

            // 'google' or 'apple'
            $table->string('provider');

            // OpenID Connect "sub" (unique per provider)
            $table->string('provider_user_id');

            // Useful profile info (may be null for Apple after first login)
            $table->string('email')->nullable();
            $table->string('name')->nullable();

            // Store tokens (we will encrypt at model level)
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->text('id_token')->nullable();

            $table->string('token_type')->nullable();
            $table->text('scopes')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_used_at')->nullable();

            $table->timestamps();

            // Prevent duplicate accounts for same provider/sub
            $table->unique(['provider', 'provider_user_id']);
            $table->index(['provider', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oauth_accounts');
    }
};
