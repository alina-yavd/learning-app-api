<?php

declare(strict_types=1);

namespace App\ViewModel;

use Doctrine\Common\Collections\Collection;

final class WordGroupDTO
{
    private int $id;
    private string $name;
    private ?Collection $words;
    private ?string $imageUrl;

    public function __construct(int $id, string $name, Collection $words = null, string $imageUrl = null)
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

    public function getWords(): ?Collection
    {
        return $this->words ? $this->words->map(function ($item) {
            return $item->getInfo();
        }) : null;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }
}
