<?php

namespace App\Service\User;

use App\DTO\ApiTokenDTO;
use Symfony\Component\HttpFoundation\Request;

interface UserAuthInterface
{
    public function login(Request $request): ApiTokenDTO;

    public function refreshToken(Request $request): ApiTokenDTO;

    public function register(Request $request): ApiTokenDTO;
}
