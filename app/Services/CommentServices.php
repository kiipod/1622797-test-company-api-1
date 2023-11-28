<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class CommentServices
{
    /**
     * Метод отвечает за создание нового комментария
     *
     * @param array $params
     * @param int $userId
     * @param int $companyId
     * @return Comment
     * @throws InternalErrorException
     */
    public function addNewComment(array $params, int $userId, int $companyId): Comment
    {
        $comment = new Comment();

        $comment->text = $params['text'];
        $comment->rating = $params['rating'];
        $comment->company_id = $companyId;
        $comment->user_id = $userId;

        if (!$comment->save()) {
            throw new InternalErrorException('Не удалось сохранить комментарий', 500);
        }

        return $comment;
    }

    /**
     * Метод отвечает за удаление комментария
     *
     * @param int $commentId
     * @return void
     */
    public function deleteComment(int $commentId): void
    {
        $comment = Comment::whereId($commentId)->firstOrFail();
        $comment->delete();
    }

    /**
     * Метод отвечает за обновление комментария
     *
     * @throws InternalErrorException
     */
    public function updateComment(int $commentId, array $params): Model
    {
        $comment = Comment::whereId($commentId)->firstOrFail();

        $comment->text = $params['text'];
        $newRating = $params['rating'];

        if ($newRating) {
            $comment->rating = $newRating;
        }

        if (!$comment->save()) {
            throw new InternalErrorException('Не удалось изменить комментарий', 500);
        }

        return $comment;
    }
}
