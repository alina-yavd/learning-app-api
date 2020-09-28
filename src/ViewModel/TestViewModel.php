<?php

namespace App\ViewModel;

use App\Collection\WordTranslations;

final class TestViewModel
{
    private WordViewModel $word;
    private WordTranslations $answers;
    private ?WordGroupViewModel $group;

    public function __construct(
        WordViewModel $word,
        WordTranslations $answers,
        ?WordGroupViewModel $group
    ) {
        $this->word = $word;
        $this->answers = $answers;
        $this->group = $group;
    }

    public function getWord(): WordViewModel
    {
        return $this->word;
    }

    public function getAnswers(): WordTranslations
    {
        return $this->answers;
    }

    public function getGroup(): ?WordGroupViewModel
    {
        return $this->group;
    }
}
