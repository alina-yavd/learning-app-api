<?php

declare(strict_types=1);

namespace App\ViewModel;

use App\Collection\WordAnswers;

final class WordDTO
{
    private int $id;
    private string $text;
    private ?WordAnswers $answers;
    private ?WordGroupDTO $group;

    public function __construct(int $id, string $text, WordAnswers $answers = null, WordGroupDTO $group = null)
    {
        $this->id = $id;
        $this->text = $text;
        $this->answers = $answers;
        $this->group = $group;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getAnswers(): ?WordAnswers
    {
        return $this->answers;
    }

    public function getGroup(): ?WordGroupDTO
    {
        return $this->group;
    }

    public function getInfo(): array
    {
        return [
            'id' => $this->getId(),
            'text' => $this->getText(),
        ];
    }

    public function getAnswersInfo(): array
    {
        $answersInfo = $this->getAnswers() ? $this->getAnswers()->map(function ($item) {
            return $item->getInfo();
        }) : null;

        return array_merge($this->getInfo(), ['answers' => $answersInfo]);
    }

    public function getFullInfo(): array
    {
        $fullInfo = ['group' => $this->getGroup() ? $this->getGroup()->getInfo() : null];

        return array_merge($this->getAnswersInfo(), $fullInfo);
    }
}
