<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserDataDTO
{
    /**
     * @Assert\Email
     */
    private ?string $email;

    private ?string $password;

    private ?string $firstName;

    private ?string $lastName;

    public function __construct($email = null, $password = null, $firstName = null, $lastName = null)
    {
        $this->email = $email;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function hasEmail(): bool
    {
        return null !== $this->email;
    }

    public function hasPassword(): bool
    {
        return null !== $this->password;
    }

    public function hasFirstName(): bool
    {
        return null !== $this->firstName;
    }

    public function hasLastName(): bool
    {
        return null !== $this->lastName;
    }
}
