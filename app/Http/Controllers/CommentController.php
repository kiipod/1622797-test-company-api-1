<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Responses\NotFoundResponse;
use App\Http\Responses\SuccessResponse;
use App\Models\Comment;
use App\Models\Company;
use App\Services\CommentServices;
use App\Services\CompanyServices;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class CommentController extends Controller
{
    /**
     * @param Comment $commentModel
     * @return void
     */
    public function __construct(
        private Comment $commentModel,
        private CommentServices $commentServices,
        private CompanyServices $companyServices
    ) {
    }

    /**
     * Метод отвечает за показ всех комментариев у компании
     *
     * @param int $companyId
     * @return NotFoundResponse|SuccessResponse
     */
    public function index(int $companyId): NotFoundResponse|SuccessResponse
    {
        $company = Company::whereId($companyId)->first();

        if (!$company) {
            return new NotFoundResponse();
        }

        $comments = $this->commentModel->getCompanyComment($companyId);

        return new SuccessResponse(data: ['comments' => $comments]);
    }

    /**
     * Метод отвечает за создание комментария
     *
     * @param CommentRequest $request
     * @param int $companyId
     * @return SuccessResponse
     * @throws InternalErrorException
     * @throws \Throwable
     */
    public function create(CommentRequest $request, int $companyId): SuccessResponse
    {
        $params = $request->validated();
        $user = Auth::user();

        DB::beginTransaction();

        try {
            $newComment = $this->commentServices->addNewComment($params, $user->id, $companyId);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return new SuccessResponse(data: ['comment' => $newComment]);
    }

    /**
     * Метод отвечает за редактирование комментария
     *
     * @param CommentRequest $request
     * @return SuccessResponse
     * @throws InternalErrorException
     * @throws \Throwable
     */
    public function edit(CommentRequest $request): SuccessResponse
    {
        $currentComment = $request->findComment();

        $params = $request->validated();
        $commentId = $currentComment->id;
        $companyId = $currentComment->company_id;
        $newCommentRating = $params['rating'];

        DB::beginTransaction();

        try {
            $updatedComment = $this->commentServices->updateComment($commentId, $params);

            if ($newCommentRating) {
                $this->companyServices->calculateRatingCompany($companyId);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return new SuccessResponse($updatedComment);
    }

    /**
     * Метод отвечает за удаление комментария
     *
     * @param int $commentId
     * @return NotFoundResponse|SuccessResponse
     * @throws AuthorizationException
     * @throws \Throwable
     */
    public function destroy(int $commentId): NotFoundResponse|SuccessResponse
    {
        $currentComment = Comment::find($commentId);

        if (!$currentComment) {
            return new NotFoundResponse();
        }

        $this->authorize('destroy', $currentComment);

        DB::beginTransaction();

        try {
            $this->commentServices->deleteComment($commentId);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return new SuccessResponse(data: ['Комментарий успешно удален']);
    }
}
