<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class CompanyRequest extends FormRequest
{
    /**
     * Метод находит нужную компанию для удаления и редактирования
     *
     * @return Company|null
     */
    public function findCompany(): ?Company
    {
        return Company::find($this->route('id'));
    }

    /**
     * Метод проверяет права пользователя для редактирования и удаления комментария
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if ($this->isMethod('patch')) {
            $company = $this->findCompany();
            return $this->user()->can('edit', $company);
        }

        if ($this->isMethod('delete')) {
            $company = $this->findCompany();
            return $this->user()->can('destroy', $company);
        }

        return true;
    }

    /**
     * Правила валидации полей редактирования компании
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'between:3,40'
            ],
            'description' => [
                'required',
                'string',
                'between:150,400'
            ],
            'logo' => [
                'required',
                File::image()->max(3072)
            ]
        ];
    }
}
