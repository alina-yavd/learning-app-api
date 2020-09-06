<?php

declare(strict_types=1);

namespace App\Service;

use App\ViewModel\TestDTO;

final class TestFakeProvider
{
    private WordProviderInterface $wordProvider;
    private WordTranslationsProviderInterface $answersProvider;

    public function __construct()
    {
        $this->wordProvider = new WordFakeProvider();
        $this->answersProvider = new WordTranslationsFakeProvider();
    }

    public function getTest(): TestDTO
    {
        $id = \random_int(1, 10);
        $word = $this->wordProvider->getItem($id);
        $answers = $this->answersProvider->getList();

        return new TestDTO($word, $answers);
    }

    public function checkAnswer($wordId, $answerId): bool
    {
        $answer = $this->answersProvider->getItem($answerId);
        $answers = $this->answersProvider->getList($wordId);

        // randomly make answer correct
        if (rand(0, 1)) {
            $answers->add($answer);
        }

        return $answers->contains($answer);
    }

    public function getCorrectAnswer($wordId)
    {
        return $this->answersProvider->getList($wordId);
    }
}
