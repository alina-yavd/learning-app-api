<?php

declare(strict_types=1);

namespace App\ViewModel;

use App\Collection\Words;

final class WordGroupDTO
{
    private int $id;
    private string $name;
    private ?Words $words;
    private ?string $imageUrl;

    public function __construct(int $id, string $name, Words $words = null, string $imageUrl = null)
    {
        $this->id = $id;
        $this->name = 'Group '.$name;
        $this->words = $words;
        $this->imageUrl = $imageUrl;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWords(): ?Words
    {
        return $this->words;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function getInfo(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'image' => $this->getImageUrl(),
        ];
    }
}
