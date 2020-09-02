<?php

declare(strict_types=1);

namespace App\Provider;

use App\ViewModel\TestDTO;

final class TestFakeProvider implements TestProviderInterface
{
    private WordProviderInterface $wordProvider;
    private WordAnswersProviderInterface $answersProvider;

    public function __construct()
    {
        $this->wordProvider = new WordFakeProvider();
        $this->answersProvider = new WordAnswersFakeProvider();
    }

    public function getTest(): TestDTO
    {
        $id = \random_int(1, 10);
        $word = $this->wordProvider->getItem($id);
        $answers = $this->answersProvider->getList();

        return new TestDTO($word, $answers);
    }

    public function checkAnswer($word_id, $answer_id): bool
    {
        $answer = $this->answersProvider->getItem($answer_id);
        $answers = $this->answersProvider->getList($word_id);

        // randomly make answer correct
        if (rand(0, 1)) {
            $answers->add($answer);
        }

        return $answers->contains($answer);
    }

    public function getCorrectAnswer($word_id)
    {
        return $this->answersProvider->getList($word_id);
    }
}
