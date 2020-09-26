<?php

namespace App\Controller;

use App\Exception\ApiException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ApiController implements basic method for project-specific JSON API response.
 */
abstract class ApiController extends AbstractController
{
    protected function errorExit(JsonResponse $response, string $message, $code = 406): JsonResponse
    {
        $exception = new ApiException($code, $message);
        $response->setData($exception->getErrorDetails());
        $response->setStatusCode($code);

        return $response;
    }
}
