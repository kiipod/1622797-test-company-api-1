<?php

namespace App\Http\Responses;

use Symfony\Component\HttpFoundation\Response;

class NotFoundResponse extends BaseResponse
{
    /**
     * @param string $message
     * @param int $statusCode
     */
    public function __construct(
        protected string $message = 'Запрашиваемая страница не существует.',
        public int $statusCode = Response::HTTP_NOT_FOUND
    ) {
        parent::__construct([], $statusCode);
    }

    /**
     * @return array|null
     */
    protected function makeResponseData(): ?array
    {
        return [
            'message' => $this->message
        ];
    }
}
