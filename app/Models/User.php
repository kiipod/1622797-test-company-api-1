<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * Таблица связанная с моделью
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Атрибуты модели, которые можно назначать массово
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'surname',
        'phone',
        'password'
    ];

    /**
     * Получение комментариев пользователя
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
