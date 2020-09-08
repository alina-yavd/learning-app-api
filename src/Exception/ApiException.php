<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException
{
    public function getErrorDetails()
    {
        return [
            'status' => 'error',
            'code' => $this->getCode() ?? 404,
            'message' => $this->getMessage() ?? 'Undefined API Exception',
        ];
    }
}
