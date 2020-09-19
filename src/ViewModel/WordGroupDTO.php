<?php

namespace App\ViewModel;

use App\Entity\Language;
use App\Entity\Word;
use Doctrine\Common\Collections\Collection;

final class WordGroupDTO
{
    private int $id;
    private string $name;
    private Language $language;
    private Language $translation;
    private ?Collection $words;

    public function __construct(int $id, string $name, Language $language, Language $translation, Collection $words = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->language = $language;
        $this->translation = $translation;
        $this->words = $words;
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
        return $this->words ? $this->words->map(function (Word $item) {
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
}
