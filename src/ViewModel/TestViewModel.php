<?php

namespace App\ViewModel;

use App\Collection\Words;

final class TestViewModel
{
    private WordViewModel $word;
    private Words $answers;
    private ?WordGroupViewModel $group;

    public function __construct(
        WordViewModel $word,
        Words $answers,
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

    public function getAnswers(): Words
    {
        return $this->answers;
    }

    public function getGroup(): ?WordGroupViewModel
    {
        return $this->group;
    }
}
