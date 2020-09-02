<?php

declare(strict_types=1);

namespace App\ViewModel;

final class WordAnswerDTO
{
    private int $id;
    private string $text;

    public function __construct(int $id, string $text)
    {
        $this->id = $id;
        $this->text = $text;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getInfo(): array
    {
        return [
            'id' => $this->getId(),
            'text' => $this->getText(),
        ];
    }
}
