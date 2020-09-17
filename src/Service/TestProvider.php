<?php

namespace App\Service;

use App\ViewModel\TestDTO;

final class TestProvider implements TestProviderInterface
{
    private WordProviderInterface $wordProvider;
    private WordTranslationsProviderInterface $translationsProvider;
    private WordGroupProviderInterface $groupProvider;
    private RandomWordProvider $randomWordProvider;

    public function __construct(
        WordProviderInterface $wordProvider,
        WordTranslationsProviderInterface $translationsProvider,
        WordGroupProviderInterface $groupProvider,
        RandomWordProvider $randomWordProvider
    ) {
        $this->wordProvider = $wordProvider;
        $this->translationsProvider = $translationsProvider;
        $this->groupProvider = $groupProvider;
        $this->randomWordProvider = $randomWordProvider;
    }

    public function getTest(?int $groupId = null): ?TestDTO
    {
        if ($groupId) {
            $group = $this->groupProvider->getItem($groupId);
        } else {
            $group = null;
        }

        $word = $this->randomWordProvider->getItem($group);
        $translation = $this->translationsProvider->getItemForWord($word->getId());
        $answers = $this->translationsProvider->getListExcludingWord($word->getId());

        $answers->add($translation);
        $answers->shuffle();

        return new TestDTO($word, $answers, $group);
    }

    public function checkAnswer(int $wordId, int $answerId): bool
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
