<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Unique;

class UserRequest extends FormRequest
{
    /**
     * Метод определяет авторизован ли пользователь
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации полей при регистрации
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:40',
            'surname' => 'required|string|min:3|max:40',
            'phone' => [
                'required',
                'string',
                'phone',
                'regex:/^\+7\d{10}$/',
                $this->getUniqRule()
            ],
            'password' => [
                $this->getPasswordRequiredRule(),
                'string',
                'min:8',
                'confirmed',
            ],
            'avatar_url' => [
                'required',
                File::image()->max(2048)
            ]
        ];
    }

    /**
     * Метод проверяет телефон на уникальность
     *
     * @return Unique
     */
    private function getUniqRule(): Unique
    {
        $rule = Rule::unique(User::class);

        if ($this->isMethod('patch') && Auth::check()) {
            return $rule->ignore(Auth::user());
        }

        return $rule;
    }

    /**
     * Метод проверяет пароль
     *
     * @return string
     */
    private function getPasswordRequiredRule(): string
    {
        return $this->isMethod('patch') ? 'sometimes' : 'required';
    }
}
