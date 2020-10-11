<?php

namespace App\Service;

use App\Entity\Language;
use App\Repository\LanguageRepository;

final class WordGroupFilter
{
    private ?array $ids;
    private ?Language $language;
    private ?Language $translation;
    private LanguageRepository $languageRepository;

    public function __construct(LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
        $this->ids = null;
        $this->language = null;
        $this->translation = null;
    }

    public function hasIds(): bool
    {
        return !empty($this->ids);
    }

    public function getIds(): ?array
    {
        return $this->ids;
    }

    public function setIds(array $ids): void
    {
        $this->ids = $ids;
    }

    public function hasLanguage(): bool
    {
        return null !== $this->language;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?string $languageCode): void
    {
        if ($languageCode) {
            $languageItem = $this->languageRepository->findOneBy(['code' => $languageCode]);
            if ($languageItem instanceof Language) {
                $this->language = $languageItem;
            }
        }
    }

    public function hasTranslation(): bool
    {
        return null !== $this->translation;
    }

    public function getTranslation(): ?Language
    {
        return $this->translation;
    }

    public function setTranslation(?string $translationCode): void
    {
        if ($translationCode) {
            $translationItem = $this->languageRepository->findOneBy(['code' => $translationCode]);
            if ($translationItem instanceof Language) {
                $this->translation = $translationItem;
            }
        }
    }
}
