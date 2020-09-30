<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ApiTokenDTO
{
    /**
     * @Assert\NotBlank
     */
    private ?string $token;

    /**
     * @Assert\NotBlank
     */
    private ?string $refreshToken;

    public function __construct($token, $refreshToken)
    {
        $this->token = $token;
        $this->refreshToken = $refreshToken;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }
}
