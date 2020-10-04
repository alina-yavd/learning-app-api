<?php

namespace App\ViewModel;

use App\Entity\Language;

final class WordTranslationViewModel
{
    private int $id;
    private string $text;
    private Language $language;

    public function __construct(int $id, string $text, Language $language)
    {
        $this->id = $id;
        $this->text = $text;
        $this->language = $language;
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
}
