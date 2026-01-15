<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Determine if user can view comments.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view comments');
    }

    /**
     * Determine if user can approve comments.
     */
    public function approve(User $user, Comment $comment): bool
    {
        return $user->can('approve comments');
    }

    /**
     * Determine if user can delete comments.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->can('delete comments');
    }

    /** Determine if user can restore soft-deleted comments. */
    public function restore(User $user, Comment $comment): bool
    {
        return $user->can('delete comments');
    }

    /** Determine if user can permanently delete comments */
    public function forceDelete(User $user, Comment $comment): bool
    {
        return $user->can('delete comments');
    }



}   

