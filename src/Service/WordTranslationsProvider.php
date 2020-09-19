<?php

namespace App\Service;

use App\Collection\WordTranslations;
use App\Entity\WordTranslation;
use App\Exception\EntityNotFoundException;
use App\Repository\WordTranslationRepository;
use App\ViewModel\WordTranslationDTO;

/**
 * Implements WordTranslationsProviderInterface for entities that are stored in database.
 */
final class WordTranslationsProvider implements WordTranslationsProviderInterface
{
    private WordTranslationRepository $repository;
    const TRANSLATIONS_COUNT = 3;

    public function __construct(WordTranslationRepository $translationRepository)
    {
        $this->repository = $translationRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getItem(int $id): WordTranslationDTO
    {
        $item = $this->repository->find($id);

        if (null == $item) {
            throw EntityNotFoundException::byId('Word translation', $id);
        }

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
    public function getItemForWord(int $wordId): WordTranslationDTO
    {
        $items = $this->repository->findBy(['word' => $wordId]);
        $key = \array_rand($items);

        return $items[$key]->getItem();
    }

    /**
     * {@inheritdoc}
     */
    public function getListExcludingWord(int $wordId): WordTranslations
    {
        $items = $this->repository->findAllExcludingWord($wordId);

        $viewModels = [];
        for ($i = 0; $i < self::TRANSLATIONS_COUNT; ++$i) {
            $randomKey = array_rand($items);
            $randomItem = $items[$randomKey];
            $viewModels[] = $randomItem->getItem();
            unset($items[$randomKey]);
        }

        return new WordTranslations(...$viewModels);
    }
}
