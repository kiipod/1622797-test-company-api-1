<?php

namespace App\Http\Responses;

use Symfony\Component\HttpFoundation\Response;

class FailResponse extends BaseResponse
{
    /**
     * @param string $message
     * @param int $statusCode
     */
    public function __construct(
        protected string $message = 'Истекло время ожидания, повторите попытку.',
        public int $statusCode = Response::HTTP_REQUEST_TIMEOUT
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
