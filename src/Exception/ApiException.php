<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

final class ApiException extends HttpException
{
    public function getErrorDetails()
    {
        return [
            'status' => 'error',
            'message' => $this->getMessage() ?? 'Undefined API Exception.',
        ];
    }
}
