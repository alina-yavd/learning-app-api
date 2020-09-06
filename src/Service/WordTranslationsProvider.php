<?php

declare(strict_types=1);

namespace App\Service;

use App\Collection\WordTranslations;
use App\Entity\WordTranslation;
use App\Repository\WordTranslationRepository;
use App\ViewModel\WordTranslationDTO;

final class WordTranslationsProvider implements WordTranslationsProviderInterface
{
    private WordTranslationRepository $repository;

    public function __construct(WordTranslationRepository $translationRepository)
    {
        $this->repository = $translationRepository;
    }

    public function getItem(int $id): WordTranslationDTO
    {
        $translation = $this->repository->find($id);

        return $translation->getItem();
    }

    public function getList(): WordTranslations
    {
        $translations = $this->repository->findAll();

        $viewModels = \array_map(fn (WordTranslation $translation) => $translation->getItem(), $translations);

        return new WordTranslations(...$viewModels);
    }

    public function getItemForWord($wordId): WordTranslationDTO
    {
        $translations = $this->repository->findBy(['word' => $wordId]);
        $key = \array_rand($translations);

        return $translations[$key]->getItem();
    }

    public function getListExcludingWord($wordId): WordTranslations
    {
        $qb = $this->repository
            ->createQueryBuilder('t')
            ->where('t.word != :word_id')
            ->setParameter('word_id', $wordId)
            ->getQuery()
        ;

        $translations = $qb->getResult();

        $viewModels = \array_map(fn (WordTranslation $translation) => $translation->getItem(), $translations);

        return new WordTranslations(...$viewModels);
    }
}
