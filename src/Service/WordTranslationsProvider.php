<?php

namespace App\Service;

use App\Collection\WordTranslations;
use App\Entity\WordTranslation;
use App\Repository\WordTranslationRepository;
use App\ViewModel\WordTranslationViewModel;

/**
 * Implements WordTranslationsProviderInterface for entities that are stored in database.
 */
final class WordTranslationsProvider implements WordTranslationsProviderInterface
{
    private WordTranslationRepository $repository;

    public function __construct(WordTranslationRepository $translationRepository)
    {
        $this->repository = $translationRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getItem(int $id): WordTranslationViewModel
    {
        $item = $this->repository->getById($id);

        return $item->getItem();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(): WordTranslations
    {
        $items = $this->repository->findAll();

        $viewModels = \array_map(fn (WordTranslation $item) => $item->getItem(), $items);

        return new WordTranslations(...$viewModels);
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsForWord(int $wordId): WordTranslations
    {
        $items = $this->repository->findBy(['word' => $wordId]);

        $viewModels = \array_map(fn (WordTranslation $item) => $item->getItem(), $items);

        return new WordTranslations(...$viewModels);
    }

    /**
     * {@inheritdoc}
     */
    public function getRandomList(int $count, WordFilter $filter): WordTranslations
    {
        $items = $this->repository->getList($filter);

        $viewModels = [];
        for ($i = 0; $i < $count; ++$i) {
            $randomKey = array_rand($items);
            $randomItem = $items[$randomKey];
            $viewModels[] = $randomItem->getItem();
            unset($items[$randomKey]);
        }

        return new WordTranslations(...$viewModels);
    }
}
