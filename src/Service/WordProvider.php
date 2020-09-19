<?php

namespace App\Service;

use App\Collection\Words;
use App\Entity\Word;
use App\Exception\EntityNotFoundException;
use App\Repository\WordGroupRepository;
use App\Repository\WordRepository;
use App\ViewModel\WordDTO;

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
    public function getItem(int $id): WordDTO
    {
        $item = $this->repository->find($id);

        if (null == $item) {
            throw EntityNotFoundException::byId('Word', $id);
        }

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
}
