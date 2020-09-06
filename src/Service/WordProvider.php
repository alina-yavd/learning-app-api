<?php

declare(strict_types=1);

namespace App\Service;

use App\Collection\Words;
use App\Entity\Word;
use App\Repository\WordRepository;
use App\ViewModel\WordDTO;

final class WordProvider implements WordProviderInterface
{
    private WordRepository $repository;

    public function __construct(WordRepository $wordRepository)
    {
        $this->repository = $wordRepository;
    }

    public function getItem(int $id): WordDTO
    {
        $word = $this->repository->find($id);

        return $word->getItem();
    }

    public function getList(): Words
    {
        $words = $this->repository->findAll();

        $viewModels = \array_map(fn (Word $word) => $word->getItem(), $words);

        return new Words(...$viewModels);
    }
}
