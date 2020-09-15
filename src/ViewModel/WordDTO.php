<?php

namespace App\ViewModel;

use App\Entity\WordGroup;
use App\Entity\WordTranslation;
use Doctrine\Common\Collections\Collection;

final class WordDTO
{
    private int $id;
    private string $text;
    private ?Collection $translations;
    private ?Collection $groups;

    public function __construct(int $id, string $text, Collection $translations = null, Collection $groups = null)
    {
        $this->id = $id;
        $this->text = $text;
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

    public function getTranslations(): ?Collection
    {
        return $this->translations ? $this->translations->map(function (WordTranslation $item) {
            return $item->getInfo();
        }) : null;
    }

    public function getGroups(): ?Collection
    {
        return $this->groups ? $this->groups->map(function (WordGroup $item) {
            return $item->getInfo();
        }) : null;
    }
}
