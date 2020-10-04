<?php

namespace App\Service;

use App\Entity\Language;

final class WordFilter
{
    private ?int $includeId;
    private ?int $excludeId;
    private ?Language $language;

    public function __construct()
    {
        $this->includeId = null;
        $this->excludeId = null;
        $this->language = null;
    }

    public function hasIncludeId(): bool
    {
        return null !== $this->includeId;
    }

    public function getIncludeId(): ?int
    {
        return $this->includeId;
    }

    public function setIncludeId(?int $includeId): void
    {
        $this->includeId = $includeId;
    }

    public function hasExcludeId(): bool
    {
        return null !== $this->excludeId;
    }

    public function getExcludeId(): ?int
    {
        return $this->excludeId;
    }

    public function setExcludeId(int $id): void
    {
        $this->excludeId = $id;
    }

    public function hasLanguage(): bool
    {
        return null !== $this->language;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): void
    {
        $this->language = $language;
    }
}
