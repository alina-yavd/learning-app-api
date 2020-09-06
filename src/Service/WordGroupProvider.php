<?php

declare(strict_types=1);

namespace App\Service;

use App\Collection\WordGroups;
use App\Entity\WordGroup;
use App\Repository\WordGroupRepository;
use App\ViewModel\WordGroupDTO;

final class WordGroupProvider implements WordGroupsProviderInterface
{
    private WordGroupRepository $repository;

    public function __construct(WordGroupRepository $wordGroupsRepository)
    {
        $this->repository = $wordGroupsRepository;
    }

    public function getItem(int $id): WordGroupDTO
    {
        $wordGroup = $this->repository->find($id);

        return $wordGroup->getItem();
    }

    public function getList(): WordGroups
    {
        $wordGroups = $this->repository->findAll();

        $viewModels = \array_map(fn (WordGroup $wordGroup) => $wordGroup->getItem(), $wordGroups);

        return new WordGroups(...$viewModels);
    }
}
