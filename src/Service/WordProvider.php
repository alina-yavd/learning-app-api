<?php

namespace App\Service;

use App\Collection\Words;
use App\Entity\Word;
use App\Repository\WordGroupRepository;
use App\Repository\WordRepository;
use App\ViewModel\WordViewModel;

/**
 * Implements WordProviderInterface for entities that are stored in database.
 */
final class WordProvider implements WordProviderInterface
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
    public function getItem(int $id): WordViewModel
    {
        $item = $this->repository->getById($id);

        return $item->getItem();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(): Words
    {
        $items = $this->repository->findAll();

        $viewModels = \array_map(fn (Word $item) => $item->getItem(), $items);

        return new Words(...$viewModels);
    }

    public function getEntity(int $id): Word
    {
        return $this->repository->getById($id);
    }
}
