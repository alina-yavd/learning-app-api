<?php

namespace App\Service\User;

use App\DTO\ApiTokenDTO;
use App\DTO\UserDTO;
use App\Entity\ApiRefreshToken;

interface UserAuthInterface
{
    public function login(UserDTO $userDTO): ApiTokenDTO;

    public function refreshToken(ApiRefreshToken $refreshToken): ApiTokenDTO;

    public function register(UserDTO $userDTO): ApiTokenDTO;
}
