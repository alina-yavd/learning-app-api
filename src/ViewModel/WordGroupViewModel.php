<?php

namespace App\ViewModel;

use App\Entity\Language;
use App\Entity\Word;
use Doctrine\Common\Collections\Collection;

final class WordGroupViewModel
{
    private int $id;
    private string $name;
    private Language $language;
    private Language $translation;
    private ?Collection $words;
    private float $progress;

    public function __construct(int $id, string $name, Language $language, Language $translation, Collection $words = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->language = $language;
        $this->translation = $translation;
        $this->words = $words;
        $this->progress = 0;
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
        return $this->words;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function getTranslation(): Language
    {
        return $this->translation;
    }

    public function getProgress(): float
    {
        return $this->progress;
    }

    public function setProgress(?float $progress): void
    {
        $this->progress = (int) $progress;
    }
}
