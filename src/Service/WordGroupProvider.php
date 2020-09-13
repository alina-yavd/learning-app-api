<?php

declare(strict_types=1);

namespace App\Service;

use App\Collection\WordGroups;
use App\Entity\Language;
use App\Entity\Word;
use App\Entity\WordGroup;
use App\Exception\EntityNotFoundException;
use App\Repository\LanguageRepository;
use App\Repository\WordGroupRepository;
use App\ViewModel\WordGroupDTO;
use Doctrine\ORM\EntityManagerInterface;

final class WordGroupProvider implements WordGroupProviderInterface
{
    private EntityManagerInterface $em;
    private WordGroupRepository $repository;
    private LanguageRepository $languageRepository;

    public function __construct(
        EntityManagerInterface $em,
        WordGroupRepository $wordGroupRepository,
        LanguageRepository $languageRepository
    ) {
        $this->em = $em;
        $this->repository = $wordGroupRepository;
        $this->languageRepository = $languageRepository;
    }

    public function getItem(int $id): WordGroupDTO
    {
        $item = $this->repository->find($id);

        if (null === $item) {
            throw new EntityNotFoundException('Word group', $id);
        }

        return $item->getItem();
    }

    public function getItemByName(string $name): ?WordGroup
    {
        return $this->repository->findOneBy(['name' => $name]);
    }

    public function getList(?array $filter): WordGroups
    {
        $filterParams = $this->getFilterParams($filter);
        if (!empty($filterParams)) {
            $items = $this->repository->findBy($filterParams);
        } else {
            $items = $this->repository->findAll();
        }

        $viewModels = \array_map(fn (WordGroup $item) => $item->getItem(), $items);

        return new WordGroups(...$viewModels);
    }

    public function removeItem(int $id, $deleteWithData = false)
    {
        $item = $this->repository->find($id);

        if (null === $item) {
            throw new EntityNotFoundException('Word group', $id);
        }

        if ($deleteWithData) {
            $words = $item->getWords();
            $words->map(function (Word $word) {
                if ($word->getGroups()->count() <= 1) {
                    $this->em->remove($word);
                }
            });
        }

        $this->em->remove($item);
        $this->em->flush();
    }

    private function getFilterParams($filter)
    {
        $filterParams = [];

        if (!empty($filter['language'])) {
            $language = $this->languageRepository->findOneBy(['code' => $filter['language']]);
            if ($language instanceof Language) {
                $filterParams['language'] = $language->getId();
            }
        }

        if (!empty($filter['translation'])) {
            $translation = $this->languageRepository->findOneBy(['code' => $filter['translation']]);
            if ($translation instanceof Language) {
                $filterParams['translation'] = $translation->getId();
            }
        }

        return $filterParams;
    }
}
