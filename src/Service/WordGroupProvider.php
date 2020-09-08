<?php

declare(strict_types=1);

namespace App\Service;

use App\Collection\WordGroups;
use App\Entity\WordGroup;
use App\Exception\EntityNotFoundException;
use App\Repository\WordGroupRepository;
use App\ViewModel\WordGroupDTO;

final class WordGroupProvider implements WordGroupProviderInterface
{
    private WordGroupRepository $repository;

    public function __construct(WordGroupRepository $wordGroupRepository)
    {
        $this->repository = $wordGroupRepository;
    }

    public function getItem(int $id): WordGroupDTO
    {
        $item = $this->repository->find($id);

        if (null == $item) {
            throw new EntityNotFoundException('Word group', $id);
        }

        return $item->getItem();
    }

    public function getItemByName(string $name): ?WordGroup
    {
        return $this->repository->findOneBy(['name' => $name]);
    }

    public function getList(): WordGroups
    {
        $items = $this->repository->findAll();

        $viewModels = \array_map(fn (WordGroup $item) => $item->getItem(), $items);

        return new WordGroups(...$viewModels);
    }
}
