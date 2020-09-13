<?php

declare(strict_types=1);

namespace App\Service;

use App\Collection\WordTranslations;
use App\Entity\WordTranslation;
use App\Exception\EntityNotFoundException;
use App\Repository\WordTranslationRepository;
use App\ViewModel\WordTranslationDTO;

final class WordTranslationsProvider implements WordTranslationsProviderInterface
{
    private WordTranslationRepository $repository;
    const TRANSLATIONS_COUNT = 3;

    public function __construct(WordTranslationRepository $translationRepository)
    {
        $this->repository = $translationRepository;
    }

    public function getItem(int $id): WordTranslationDTO
    {
        $item = $this->repository->find($id);

        if (null == $item) {
            throw new EntityNotFoundException('Word translation', $id);
        }

        return $item->getItem();
    }

    public function getList(): WordTranslations
    {
        $items = $this->repository->findAll();

        $viewModels = \array_map(fn (WordTranslation $item) => $item->getItem(), $items);

        return new WordTranslations(...$viewModels);
    }

    public function getItemsForWord($wordId): WordTranslations
    {
        $items = $this->repository->findBy(['word' => $wordId]);

        $viewModels = \array_map(fn (WordTranslation $item) => $item->getItem(), $items);

        return new WordTranslations(...$viewModels);
    }

    public function getItemForWord($wordId): WordTranslationDTO
    {
        $items = $this->repository->findBy(['word' => $wordId]);
        $key = \array_rand($items);

        return $items[$key]->getItem();
    }

    public function getListExcludingWord($wordId): WordTranslations
    {
        $qb = $this->repository
            ->createQueryBuilder('t')
            ->where('t.word != :word_id')
            ->setParameter('word_id', $wordId)
            ->getQuery();

        $items = $qb->getResult();

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
