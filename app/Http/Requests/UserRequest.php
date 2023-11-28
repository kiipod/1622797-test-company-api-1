<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
                'regex:/^\+7\d{10}$/'
            ],
            'password' => [
                $this->getPasswordRequiredRule(),
                'string',
                'min:8',
                'confirmed',
            ],
            'avatar_url' => 'nullable|image|max:2048'
        ];
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
