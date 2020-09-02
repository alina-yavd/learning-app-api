<?php

declare(strict_types=1);

namespace App\ViewModel;

use App\Collection\WordAnswers;

final class TestDTO
{
    private WordDTO $word;
    private WordAnswers $answers;

    public function __construct(
        WordDTO $word,
        WordAnswers $answers
    ) {
        $this->word = $word;
        $this->answers = $answers;
    }

    public function getWord(): WordDTO
    {
        return $this->word;
    }

    public function getAnswers(): WordAnswers
    {
        return $this->answers;
    }
}
