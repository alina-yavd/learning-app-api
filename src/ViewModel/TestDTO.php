<?php

namespace App\ViewModel;

use App\Collection\WordTranslations;

final class TestDTO
{
    private WordDTO $word;
    private WordTranslations $answers;
    private ?WordGroupDTO $group;

    public function __construct(
        WordDTO $word,
        WordTranslations $answers,
        ?WordGroupDTO $group
    ) {
        $this->word = $word;
        $this->answers = $answers;
        $this->group = $group;
    }

    public function getWord(): WordDTO
    {
        return $this->word;
    }

    public function getAnswers(): WordTranslations
    {
        return $this->answers;
    }

    public function getGroup(): ?WordGroupDTO
    {
        return $this->group;
    }
}
