<?php

namespace App\Services;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use App\Models\Film;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class CompanyServices
{
    /**
     * Метод осуществляет поиск фильма по id
     *
     * @param int $companyId
     * @param array $columns
     * @return Model|null
     */
    public function getCompanyById(int $companyId, array $columns = ['*']): ?Model
    {
        return Company::with([
            'comments'
        ])->where('id', '=', $companyId)->firstOrFail($columns);
    }

    /**
     * Метод отвечает за создание нового комментария
     *
     * @param array $params
     * @param int $userId
     * @return Company
     * @throws InternalErrorException
     */
    public function addNewCompany(array $params, int $userId): Company
    {
        $company = new Company();

        $company->title = $params['title'];
        $company->description = $params['description'];
        $company->user_id = $userId;
        $company->logo = $params['logo'];

        if (!$company->save()) {
            throw new InternalErrorException('Не удалось сохранить компанию', 500);
        }

        return $company;
    }

    /**
     * Метод отвечает за удаление комментария
     *
     * @param int $companyId
     * @return void
     */
    public function deleteCompany(int $companyId): void
    {
        $company = Company::whereId($companyId)->firstOrFail();
        $company->delete();
    }

    /**
     * Метод отвечает за обновление профиля пользователя
     *
     * @param CompanyRequest $request
     * @param Company $company
     * @return Company
     */
    public function updateCompany(CompanyRequest $request, Company $company): Company
    {
        $params = $request->toArray();

        if (isset($params['title'])) {
            $company->title = $params['title'];
        }

        if (isset($params['description'])) {
            $company->description = $params['description'];
        }

        if ($request->hasFile('logo')) {
            $newLogo = $request->file('logo');
            $oldLogo = $company->logo;
            if ($oldLogo) {
                Storage::delete($oldLogo);
            }
            $filename = $newLogo->store('public/logos', 'local');
            $company['logo'] = $filename;
        }

        $company->update();

        return $company;
    }

    /**
     * Получение общей оценки компании
     *
     * @param int $companyId
     * @return int|float
     */
    public function calculateRatingCompany(int $companyId): int|float
    {
        $company = Company::whereId($companyId)->first();

        $companyRating = $company->comments()->avg('rating');
        return $companyRating ? round($companyRating, 1) : 0;
    }

    /**
     * Получение топ-10 компаний по оценке
     *
     * @return mixed
     */
    public function topTenCompany(): mixed
    {
        return Company::select(
            'companies.id',
            'companies.title',
            'companies.description',
            'companies.logo'
        )
            ->leftJoin('comments', 'companies.id', '=', 'comments.company_id')
            ->groupBy(
                'companies.id',
                'companies.title',
                'companies.description',
                'companies.logo'
            )
            ->orderByDesc(\DB::raw('AVG(comments.rating)'))
            ->limit(10)
            ->get();
    }
}
