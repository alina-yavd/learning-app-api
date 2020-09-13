<?php

declare(strict_types=1);

namespace App\ViewModel;

use App\Collection\WordTranslations;

final class TestDTO
{
    private WordDTO $word;
    private WordTranslations $answers;

    public function __construct(
        WordDTO $word,
        WordTranslations $answers
    ) {
        $this->word = $word;
        $this->answers = $answers;
    }

    public function getWord(): WordDTO
    {
        return $this->word;
    }

    public function getAnswers(): WordTranslations
    {
        return $this->answers;
    }
}
