<?php

declare(strict_types=1);

namespace App\ViewModel;

use App\Entity\Language;
use Doctrine\Common\Collections\Collection;

final class WordGroupDTO
{
    private int $id;
    private string $name;
    private Language $language;
    private Language $translation;
    private ?Collection $words;
    private ?string $imageUrl;

    public function __construct(int $id, string $name, Language $language, Language $translation, Collection $words = null, string $imageUrl = null)
    {
        $this->id = $id;
        $this->name = 'Group '.$name;
        $this->language = $language;
        $this->translation = $translation;
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

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function getTranslation(): Language
    {
        return $this->translation;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }
}
