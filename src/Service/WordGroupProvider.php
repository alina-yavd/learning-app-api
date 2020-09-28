<?php

namespace App\Service;

use App\Collection\WordGroups;
use App\Entity\Word;
use App\Entity\WordGroup;
use App\Repository\WordGroupRepository;
use App\ViewModel\WordGroupViewModel;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Implements WordGroupProviderInterface for entities that are stored in database.
 */
final class WordGroupProvider implements WordGroupProviderInterface
{
    private EntityManagerInterface $em;
    private WordGroupRepository $repository;

    public function __construct(
        EntityManagerInterface $em,
        WordGroupRepository $wordGroupRepository
    ) {
        $this->em = $em;
        $this->repository = $wordGroupRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getItem(int $id): WordGroupViewModel
    {
        $item = $this->repository->getById($id);

        return $item->getItem();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityByName(string $name): WordGroup
    {
        return $this->repository->getByName($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getList(WordGroupFilter $filter): WordGroups
    {
        $items = $this->repository->getByFilter($filter);

        $viewModels = \array_map(fn (WordGroup $item) => $item->getItem(), $items);

        return new WordGroups(...$viewModels);
    }

    /**
     * {@inheritdoc}
     */
    public function removeItem(int $id): void
    {
        $item = $this->repository->getById($id);

        $this->em->remove($item);
        $this->em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function removeItemWithWords(int $id): void
    {
        $item = $this->repository->getById($id);

        $words = $item->getWords();
        $words->map(function (Word $word) {
            if ($word->getGroups()->count() <= 1) {
                $this->em->remove($word);
            }
        });

        $this->em->remove($item);
        $this->em->flush();
    }
}
