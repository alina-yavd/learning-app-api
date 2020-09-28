<?php

namespace App\Service;

use App\ViewModel\TestViewModel;

/**
 * Implements TestProviderInterface for entities that are stored in database.
 */
final class TestProvider implements TestProviderInterface
{
    private WordProviderInterface $wordProvider;
    private WordTranslationsProviderInterface $translationsProvider;
    private WordGroupProviderInterface $groupProvider;
    private RandomWordProviderInterface $randomWordProvider;

    public function __construct(
        WordProviderInterface $wordProvider,
        WordTranslationsProviderInterface $translationsProvider,
        WordGroupProviderInterface $groupProvider,
        RandomWordProviderInterface $randomWordProvider
    ) {
        $this->wordProvider = $wordProvider;
        $this->translationsProvider = $translationsProvider;
        $this->groupProvider = $groupProvider;
        $this->randomWordProvider = $randomWordProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function getTest(?int $groupId = null): ?TestViewModel
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

        return new TestViewModel($word, $answers, $group);
    }

    /**
     * {@inheritdoc}
     */
    public function checkAnswer(int $wordId, int $answerId): bool
    {
        $answer = $this->translationsProvider->getItem($answerId);
        $answers = $this->translationsProvider->getItemsForWord($wordId);

        return $answers->contains($answer);
    }
}
