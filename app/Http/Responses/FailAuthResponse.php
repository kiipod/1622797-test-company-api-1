<?php

namespace App\Http\Responses;

use Symfony\Component\HttpFoundation\Response;

class FailAuthResponse extends BaseResponse
{
    /**
     * @param string $message
     * @param int $statusCode
     */
    public function __construct(
        protected string $message = 'Запрос требует аутентификации.',
        public int $statusCode = Response::HTTP_UNAUTHORIZED
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
