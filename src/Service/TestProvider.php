<?php

declare(strict_types=1);

namespace App\Service;

use App\ViewModel\TestDTO;
use App\ViewModel\WordDTO;

final class TestProvider implements TestProviderInterface
{
    private WordProviderInterface $wordProvider;
    private WordTranslationsProviderInterface $answersProvider;

    public function __construct(WordProviderInterface $wordProvider, WordTranslationsProviderInterface $answersProvider)
    {
        $this->wordProvider = $wordProvider;
        $this->answersProvider = $answersProvider;
    }

    public function getTest(): ?TestDTO
    {
        $word = $this->getWordWithAnswers();

        $translation = $this->answersProvider->getItemForWord($word->getId());
        $answers = $this->answersProvider->getListExcludingWord($word->getId());

        $answers->add($translation);

        return new TestDTO($word, $answers);
    }

    private function getWordWithAnswers(): WordDTO
    {
        $id = \random_int(1, 4); // TODO: get words count from DB
        $word = $this->wordProvider->getItem($id);

        if (count($word->getTranslations()) < 1) {
            return $this->getWordWithAnswers();
        }

        return $word;
    }

    public function checkAnswer($wordId, $answerId): bool
    {
        $answer = $this->answersProvider->getItem($answerId);
        $answers = $this->answersProvider->getItemForWord($wordId);

        // randomly make answer correct
        if (rand(0, 1)) {
            $answers->add($answer);
        }

        return $answers->contains($answer);
    }

    public function getCorrectAnswer($wordId)
    {
        return $this->answersProvider->getItemForWord($wordId);
    }
}
