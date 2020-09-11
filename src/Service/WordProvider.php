<?php

declare(strict_types=1);

namespace App\Service;

use App\Collection\Words;
use App\Entity\Word;
use App\Exception\EntityNotFoundException;
use App\Repository\WordGroupRepository;
use App\Repository\WordRepository;
use App\ViewModel\WordDTO;
use App\ViewModel\WordGroupDTO;

final class WordProvider implements WordProviderInterface
{
    private WordRepository $repository;
    private WordGroupRepository $groupRepository;

    public function __construct(WordRepository $wordRepository, WordGroupRepository $groupRepository)
    {
        $this->repository = $wordRepository;
        $this->groupRepository = $groupRepository;
    }

    public function getItem(int $id): WordDTO
    {
        $item = $this->repository->find($id);

        if (null == $item) {
            throw new EntityNotFoundException('Word', $id);
        }

        return $item->getItem();
    }

    public function getRandom(): ?WordDTO
    {
        $wordIds = $this->repository
            ->createQueryBuilder('w')
            ->select('w.id')
            ->getQuery()
            ->getResult();

        $randomKey = array_rand($wordIds);
        $word = $this->repository->find($wordIds[$randomKey]['id']);

        return $word->getItem();
    }

    public function getRandomItemInGroup(WordGroupDTO $group): WordDTO
    {
        $group = $this->groupRepository->find($group->getId());
        $words = $group->getWords()->toArray();
        $key = \array_rand($words);

        return $words[$key]->getItem();
    }

    public function getList(): Words
    {
        $items = $this->repository->findAll();

        $viewModels = \array_map(fn (Word $item) => $item->getItem(), $items);

        return new Words(...$viewModels);
    }
}
