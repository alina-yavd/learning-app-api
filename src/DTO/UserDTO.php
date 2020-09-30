<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    private ?string $email;

    /**
     * @Assert\NotBlank
     */
    private ?string $password;

    /**
     *  @Assert\Type(type={"alpha", "null"})
     */
    private ?string $firstName;

    /**
     *  @Assert\Type(type={"alpha", "null"})
     */
    private ?string $lastName;

    public function __construct($email, $password, $firstName = null, $lastName = null)
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
}
