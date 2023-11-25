<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    /**
     * Метод проверяет может ли пользователь обновить информацию о компании
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function edit(User $user, Company $company): bool
    {
        return $user->id === $company->user_id;
    }

    /**
     * Метод проверяет может ли пользователь удалить компанию
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function delete(User $user, Company $company): bool
    {
        return $user->id === $company->user_id;
    }
}
