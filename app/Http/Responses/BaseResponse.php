<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Arrayable;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseResponse implements Responsable
{
    /**
     * @param mixed $data
     * @param int $statusCode
     */
    public function __construct(
        protected mixed $data = [],
        public int $statusCode = Response::HTTP_OK
    ) {
    }

    public function toResponse($request): JsonResponse|Response
    {
        return response()->json($this->makeResponseData(), $this->statusCode);
    }

    /**
     * @return array|null
     */
    protected function prepareData(): ?array
    {
        if ($this->data instanceof Arrayable) {
            return $this->data->toArray();
        }
        return $this->data;
    }

    /**
     * @return array|null
     */
    abstract protected function makeResponseData(): ?array;
}
