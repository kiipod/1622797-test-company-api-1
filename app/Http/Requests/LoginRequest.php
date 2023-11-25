<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Метод проводит валидацию полей при входе на сайт
     *
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'phone' => [
                'required',
                'string',
                'phone',
                'regex:/^\+7\d{10}$/'
            ],
            'password' => [
                'required',
                'string'
            ]
        ];
    }
}
