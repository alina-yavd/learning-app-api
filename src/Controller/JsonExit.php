<?php

namespace App\Controller;

use App\Exception\ApiException;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Trait JsonExit used to return project-specific JSON API response.
 */
trait JsonExit
{
    protected function errorExit(JsonResponse $response, string $message, $code = 406): JsonResponse
    {
        $exception = new ApiException($code, $message);
        $response->setData($exception->getErrorDetails());
        $response->setStatusCode($code);

        return $response;
    }
}
