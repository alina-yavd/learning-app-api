<?php

namespace App\Service;

use App\Collection\Languages;
use App\Entity\Language;
use App\Exception\LanguageAlreadyExistsException;
use App\Repository\LanguageRepository;
use App\ViewModel\LanguageDTO;

/**
 * Implements LanguageServiceInterface for entities that are stored in database.
 */
final class LanguageService implements LanguageServiceInterface
{
    private LanguageRepository $repository;

    public function __construct(LanguageRepository $languageRepository)
    {
        $this->repository = $languageRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(): Languages
    {
        $items = $this->repository->findAll();

        $viewModels = \array_map(fn (Language $item) => $item->getItem(), $items);

        return new Languages(...$viewModels);
    }

    /**
     * {@inheritdoc}
     */
    public function createItem(string $code, string $name): LanguageDTO
    {
        $item = $this->repository->findOneBy(['code' => $code]);

        if (null !== $item) {
            throw new LanguageAlreadyExistsException(sprintf('Language with code "%s" already exists.', $code));
        }

        $language = new Language($code, $name);
        $this->repository->create($language);

        return $language->getItem();
    }
}
