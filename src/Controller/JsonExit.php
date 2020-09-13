<?php

namespace App\Controller;

use App\Exception\ApiException;
use Symfony\Component\HttpFoundation\JsonResponse;

trait JsonExit
{
    protected function errorExit(JsonResponse $response, string $message, $code = 406): JsonResponse
    {
        $exception = new ApiException($code, $message);
        $response->setData($exception->getErrorDetails());

        return $response;
    }
}
