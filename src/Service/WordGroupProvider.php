<?php

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

/**
 * Implements WordGroupProviderInterface for entities that are stored in database.
 */
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

    /**
     * {@inheritdoc}
     */
    public function getItem(int $id): WordGroupDTO
    {
        $item = $this->repository->find($id);

        if (null === $item) {
            throw EntityNotFoundException::byId('Word group', $id);
        }

        return $item->getItem();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityByName(string $name): WordGroup
    {
        $item = $this->repository->findOneBy(['name' => $name]);

        if (null === $item) {
            throw EntityNotFoundException::byName('Word group', $name);
        }

        return $item;
    }

    /**
     * {@inheritdoc}
     *
     * @param ?array $filter associated array of filter data in the following format:
     *                       [
     *                       'language' => 'language_code',
     *                       'translation' => 'translation_code'
     *                       ]
     *                       Non-existing languages are ignored
     */
    public function getList(array $filter = []): WordGroups
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

    /**
     * {@inheritdoc}
     */
    public function removeItem(int $id, $deleteWithData = false): void
    {
        $item = $this->repository->find($id);

        if (null === $item) {
            throw EntityNotFoundException::byId('Word group', $id);
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

    private function getFilterParams(?array $filter)
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
