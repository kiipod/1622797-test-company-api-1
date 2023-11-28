<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Http\Responses\FailResponse;
use App\Http\Responses\NotFoundResponse;
use App\Http\Responses\SuccessResponse;
use App\Models\Company;
use App\Services\CompanyServices;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    /**
     * @param CompanyServices $companyServices
     */
    public function __construct(private CompanyServices $companyServices)
    {
    }

    /**
     * Метод отвечает за просмотр страницы с компанией
     *
     * @param int $companyId
     * @return NotFoundResponse|SuccessResponse
     */
    public function show(int $companyId): NotFoundResponse|SuccessResponse
    {
        $company = $this->companyServices->getCompanyById($companyId);

        if (!$company) {
            return new NotFoundResponse();
        }

        return new SuccessResponse(data: $company);
    }

    /**
     * Метод отвечает за создание компании
     *
     * @param CompanyRequest $request
     * @return FailResponse|SuccessResponse
     */
    public function create(CompanyRequest $request): FailResponse|SuccessResponse
    {
        try {
            $data = $request->safe()->except('logo');

            $company = Company::create($data);

            return new SuccessResponse(data: $company);
        } catch (\Exception) {
            return new FailResponse();
        }
    }

    /**
     * Метод отвечает за редактирование компании
     *
     * @param CompanyRequest $request
     * @return SuccessResponse
     * @throws \Throwable
     */
    public function edit(CompanyRequest $request): SuccessResponse
    {
        $currentCompany = $request->findCompany();

        $params = $request->validated();

        DB::beginTransaction();

        try {
            $updatedCompany = $this->companyServices->updateCompany($params, $currentCompany);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return new SuccessResponse($updatedCompany);
    }

    /**
     * Метод отвечает за удаление компании
     *
     * @param int $companyId
     * @return NotFoundResponse|SuccessResponse
     * @throws AuthorizationException
     * @throws \Throwable
     */
    public function destroy(int $companyId): NotFoundResponse|SuccessResponse
    {
        $currentCompany = Company::find($companyId);

        if (!$currentCompany) {
            return new NotFoundResponse();
        }

        $this->authorize('destroy', $currentCompany);

        DB::beginTransaction();

        try {
            $this->companyServices->deleteCompany($companyId);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return new SuccessResponse(data: ['Компания успешно удалена']);
    }

    /**
     * Метод отвечает за получение рейтинга компании
     *
     * @param int $companyId
     * @return SuccessResponse
     */
    public function avgRating(int $companyId): SuccessResponse
    {
        $companyRating = $this->companyServices->calculateRatingCompany($companyId);
        return new SuccessResponse(data: ['rating' => $companyRating]);
    }

    /**
     * Метод отвечает за получение лучших 10 компаний
     *
     * @return SuccessResponse
     */
    public function bestCompany(): SuccessResponse
    {
        $bestCompany = $this->companyServices->topTenCompany();
        return new SuccessResponse(data: $bestCompany);
    }
}
