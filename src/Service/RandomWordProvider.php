<?php

namespace App\Service;

use App\Repository\WordGroupRepository;
use App\Repository\WordRepository;
use App\ViewModel\WordDTO;
use App\ViewModel\WordGroupDTO;

/**
 * Implements RandomWordProviderInterface for entities that are stored in database.
 */
final class RandomWordProvider implements RandomWordProviderInterface
{
    private WordRepository $repository;
    private WordGroupRepository $groupRepository;

    public function __construct(WordRepository $wordRepository, WordGroupRepository $groupRepository)
    {
        $this->repository = $wordRepository;
        $this->groupRepository = $groupRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getItem(?WordGroupDTO $group): WordDTO
    {
        return $this->getWordWithAnswers($group);
    }

    private function getWordWithAnswers(?WordGroupDTO $group): WordDTO
    {
        if ($group) {
            $word = $this->getRandomItemInGroup($group);
        } else {
            $word = $this->getRandom();
        }

        if (count($word->getTranslations()) < 1) {
            return $this->getWordWithAnswers($group);
        }

        return $word;
    }

    private function getRandom(): ?WordDTO
    {
        $word = $this->repository->findOneRandom();

        return $word->getItem();
    }

    private function getRandomItemInGroup(WordGroupDTO $group): WordDTO
    {
        $group = $this->groupRepository->getById($group->getId());
        $words = $group->getWords()->toArray();
        $key = \array_rand($words);

        return $words[$key]->getItem();
    }
}
