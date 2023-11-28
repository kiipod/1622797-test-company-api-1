<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Responses\NotFoundResponse;
use App\Http\Responses\SuccessResponse;
use App\Models\User;
use App\Services\UserServices;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * @param UserServices $userServices
     */
    public function __construct(
        private UserServices $userServices
    ) {
    }

    /**
     * Метод отвечает за редактирование информации о пользователе
     *
     * @param UserRequest $request
     * @return SuccessResponse|NotFoundResponse
     */
    public function edit(UserRequest $request): SuccessResponse|NotFoundResponse
    {
        $user = Auth::user();

        if (!$user) {
            return new NotFoundResponse();
        }

        $updatedUser = $this->userServices->updateUser($request, $user);

        return new SuccessResponse(data: $updatedUser);
    }

    /**
     * Метод отвечает за удаление пользователя
     *
     * @param int $userId
     * @return NotFoundResponse|SuccessResponse
     * @throws AuthorizationException
     * @throws \Throwable
     */
    public function destroy(int $userId): NotFoundResponse|SuccessResponse
    {
        $currentUser = User::find($userId);

        if (!$currentUser) {
            return new NotFoundResponse();
        }

        $this->authorize('destroy', $currentUser);

        DB::beginTransaction();

        try {
            $this->userServices->deleteUser($userId);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return new SuccessResponse(data: ['Пользователь успешно удален']);
    }
}
