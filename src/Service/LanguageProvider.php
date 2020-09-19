<?php

namespace App\Service;

use App\Collection\Languages;
use App\Entity\Language;
use App\Exception\EntityNotFoundException;
use App\Exception\LanguageCreateException;
use App\Repository\LanguageRepository;
use App\ViewModel\LanguageDTO;

/**
 * Implements LanguageProviderInterface for entities that are stored in database.
 */
final class LanguageProvider implements LanguageProviderInterface
{
    private LanguageRepository $repository;

    public function __construct(LanguageRepository $languageRepository)
    {
        $this->repository = $languageRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getItem(int $id): LanguageDTO
    {
        $item = $this->repository->find($id);

        if (null == $item) {
            throw EntityNotFoundException::byId('Language', $id);
        }

        return $item->getItem();
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
            throw new LanguageCreateException(sprintf('Language with code "%s" already exists.', $code));
        }

        $language = new Language();
        $language->setCode($code);
        $language->setName($name);
        $this->repository->create($language);

        return $language->getItem();
    }
}
