<?php

namespace App\Service;

use App\Collection\WordGroups;
use App\Entity\Word;
use App\Entity\WordGroup;
use App\Repository\WordGroupRepository;
use App\ViewModel\WordGroupViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Implements WordGroupProviderInterface for entities that are stored in database.
 */
class WordGroupProvider implements WordGroupProviderInterface
{
    protected EntityManagerInterface $em;
    protected WordGroupRepository $repository;
    private WordGroupProgressProvider $progress;
    private Security $security;

    public function __construct(
        EntityManagerInterface $em,
        WordGroupRepository $wordGroupRepository,
        WordGroupProgressProvider $progress,
        Security $security
    ) {
        $this->em = $em;
        $this->repository = $wordGroupRepository;
        $this->progress = $progress;
        $this->security = $security;
    }

    /**
     * {@inheritdoc}
     */
    public function getItem(int $id): WordGroupViewModel
    {
        $item = $this->repository->getById($id);
        $viewModel = $item->getItem();
        $viewModel->setProgress($this->getItemProgress($id));

        return $viewModel;
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

        $viewModels = \array_map(function (WordGroup $item) {
            $viewModel = $item->getItem();
            $viewModel->setProgress($this->getItemProgress($item->getId()));

            return $viewModel;
        }, $items);

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

    public function getItemProgress(int $id): ?float
    {
        $item = $this->repository->getById($id);
        $user = $this->security->getUser();
        if (!$user) {
            return null;
        }

        return $this->progress->getProgress($user, $item);
    }
}
