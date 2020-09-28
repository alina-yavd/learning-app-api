<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class TestCheckDTO
{
    /**
     * @Assert\NotBlank
     * @Assert\Positive
     */
    private ?int $wordId;

    /**
     * @Assert\NotBlank
     * @Assert\Positive
     */
    private ?int $answerId;

    public function __construct($wordId, $answerId)
    {
        $this->wordId = $wordId;
        $this->answerId = $answerId;
    }
}
