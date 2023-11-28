<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    /**
     * Таблица связанная с моделью
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * Получение пользователя оставившего комментарий
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получение компании, которой принадлежит комментарий
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Получение всех отзывов компании
     *
     * @param int $companyId
     * @return Collection
     */
    public function getCompanyComment(int $companyId): Collection
    {
        return $this->with(['user:id,name'])
            ->select(['id', 'text', 'rating', 'created_at', 'user_id'])
            ->where(['company_id' => $companyId])
            ->orderBy('created_at', 'DESC')
            ->get();
    }
}
