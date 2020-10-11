<?php

namespace App\ViewModel;

use App\Entity\Language;
use Doctrine\Common\Collections\Collection;

final class WordViewModel
{
    private int $id;
    private string $text;
    private Language $language;
    private ?Collection $translations;
    private ?Collection $groups;

    public function __construct(int $id, string $text, Language $language, ?Collection $translations = null, ?Collection $groups = null)
    {
        $this->id = $id;
        $this->text = $text;
        $this->language = $language;
        $this->translations = $translations;
        $this->groups = $groups;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function getTranslations(): ?Collection
    {
        return $this->translations;
    }

    public function getGroups(): ?Collection
    {
        return $this->groups;
    }
}
