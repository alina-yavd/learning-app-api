<?php

namespace App\Service;

use App\Entity\Language;

final class WordFilter
{
    private ?array $includeIds;
    private ?array $excludeIds;
    private ?Language $language;

    public function __construct()
    {
        $this->includeIds = null;
        $this->excludeIds = null;
        $this->language = null;
    }

    public function hasIncludeIds(): bool
    {
        return !empty($this->includeIds);
    }

    public function getIncludeIds(): ?array
    {
        return $this->includeIds;
    }

    public function setIncludeIds(?array $includeIds): void
    {
        $this->includeIds = $includeIds;
    }

    public function hasExcludeIds(): bool
    {
        return !empty($this->excludeIds);
    }

    public function getExcludeIds(): ?array
    {
        return $this->excludeIds;
    }

    public function setExcludeIds(array $ids): void
    {
        $this->excludeIds = $ids;
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
