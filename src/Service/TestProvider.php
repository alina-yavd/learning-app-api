<?php

declare(strict_types=1);

namespace App\Service;

use App\ViewModel\TestDTO;
use App\ViewModel\WordDTO;

final class TestProvider implements TestProviderInterface
{
    private WordProviderInterface $wordProvider;
    private WordTranslationsProviderInterface $translationsProvider;
    private WordGroupProviderInterface $groupProvider;

    public function __construct(WordProviderInterface $wordProvider, WordTranslationsProviderInterface $translationsProvider, WordGroupProviderInterface $groupProvider)
    {
        $this->wordProvider = $wordProvider;
        $this->translationsProvider = $translationsProvider;
        $this->groupProvider = $groupProvider;
    }

    public function getTest($group = null): ?TestDTO
    {
        $word = $this->getWordWithAnswers($group);

        $translation = $this->translationsProvider->getItemForWord($word->getId());
        $answers = $this->translationsProvider->getListExcludingWord($word->getId());

        $answers->add($translation);
        $answers->shuffle();

        return new TestDTO($word, $answers);
    }

    private function getWordWithAnswers($group): WordDTO
    {
        if ($group) {
            $word = $this->wordProvider->getRandomItemInGroup($group);
        } else {
            $word = $this->wordProvider->getRandom();
        }

        if (count($word->getTranslations()) < 1) {
            return $this->getWordWithAnswers($group);
        }

        return $word;
    }

    public function checkAnswer($wordId, $answerId): bool
    {
        $answer = $this->translationsProvider->getItem($answerId);
        $answers = $this->translationsProvider->getItemsForWord($wordId);

        return $answers->contains($answer);
    }

    public function getCorrectAnswers($wordId)
    {
        return $this->translationsProvider->getItemsForWord($wordId);
    }
}
