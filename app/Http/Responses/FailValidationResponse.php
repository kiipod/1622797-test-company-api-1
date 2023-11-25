<?php

namespace App\Http\Responses;

use Symfony\Component\HttpFoundation\Response;

class FailValidationResponse extends BaseResponse
{
    /**
     * @param $data
     * @param string $message
     * @param int $statusCode
     */
    public function __construct(
        $data,
        protected string $message = 'Переданные данные не корректны.',
        public int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY
    ) {
        parent::__construct([], $statusCode);
    }

    /**
     * @return array|null
     */
    protected function makeResponseData(): ?array
    {
        return [
            'message' => $this->message,
            'errors' => $this->prepareData()
        ];
    }
}
