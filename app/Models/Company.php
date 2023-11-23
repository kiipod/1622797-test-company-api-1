<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    /**
     * Таблица связанная с моделью
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * Атрибуты модели, которые можно назначать массово
     *
     * @var array
     */
    public $fillable = [
        'title',
        'description',
        'logo'
    ];

    /**
     * Получение списка комментов у компании
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
