<?php

namespace App\Service;

use App\Collection\Words;
use App\Entity\Word;
use App\Repository\WordGroupRepository;
use App\Repository\WordRepository;
use App\ViewModel\WordGroupViewModel;
use App\ViewModel\WordViewModel;

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
    public function getItem(?WordGroupViewModel $group): WordViewModel
    {
        return $this->getWordWithAnswers($group);
    }

    public function getRandomList(int $count, WordFilter $filter): ?Words
    {
        $items = $this->repository->getList($filter);
        if (empty($items)) {
            return null;
        }

        $viewModels = [];
        for ($i = 0; $i < $count; ++$i) {
            $randomKey = \array_rand($items);
            $randomItem = $items[$randomKey];
            $viewModels[] = $randomItem->getItem();
            unset($items[$randomKey]);
        }

        return new Words(...$viewModels);
    }

    private function getWordWithAnswers(?WordGroupViewModel $group): WordViewModel
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

    private function getRandom(): ?WordViewModel
    {
        $word = $this->repository->findOneRandom();

        return $word->getItem();
    }

    private function getRandomItemInGroup(WordGroupViewModel $group): WordViewModel
    {
        $group = $this->groupRepository->getById($group->getId());
        $words = $group->getWords()->filter(fn (Word $item) => $item->getLanguage() === $group->getLanguage());
        $key = \array_rand($words->toArray());

        return $words[$key]->getItem();
    }
}
