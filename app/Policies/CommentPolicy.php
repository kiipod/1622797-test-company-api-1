<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Метод проверяет может ли пользователь обновить комментарий
     *
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function edit(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    /**
     * Метод проверяет может ли пользователь удалить комментарий
     *
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }
}
