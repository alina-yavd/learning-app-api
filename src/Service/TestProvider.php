<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\EntityNotFoundException;
use App\ViewModel\TestDTO;
use App\ViewModel\WordDTO;
use App\ViewModel\WordGroupDTO;

final class TestProvider implements TestProviderInterface
{
    private WordProviderInterface $wordProvider;
    private WordTranslationsProviderInterface $translationsProvider;
    private WordGroupProviderInterface $groupProvider;
    private ?WordGroupDTO $group = null;

    public function __construct(WordProviderInterface $wordProvider, WordTranslationsProviderInterface $translationsProvider, WordGroupProviderInterface $groupProvider)
    {
        $this->wordProvider = $wordProvider;
        $this->translationsProvider = $translationsProvider;
        $this->groupProvider = $groupProvider;
    }

    public function setGroup($groupId): ?WordGroupDTO
    {
        try {
            $group = $this->groupProvider->getItem($groupId);
            $this->group = $group;
        } catch (EntityNotFoundException $e) {
            $this->group = null;
        }

        return $this->group;
    }

    public function getTest(): ?TestDTO
    {
        $word = $this->getWordWithAnswers();

        $translation = $this->translationsProvider->getItemForWord($word->getId());
        $answers = $this->translationsProvider->getListExcludingWord($word->getId());

        $answers->add($translation);
        $answers->shuffle();

        return new TestDTO($word, $answers);
    }

    private function getWordWithAnswers(): WordDTO
    {
        if ($this->group) {
            $word = $this->wordProvider->getRandomItemInGroup($this->group);
        } else {
            $id = \random_int(1, 4); // TODO: get words count from DB
            $word = $this->wordProvider->getItem($id);
        }

        if (count($word->getTranslations()) < 1) {
            return $this->getWordWithAnswers();
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
