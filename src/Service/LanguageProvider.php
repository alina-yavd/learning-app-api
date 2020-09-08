<?php

declare(strict_types=1);

namespace App\Service;

use App\Collection\Languages;
use App\Entity\Language;
use App\Exception\EntityNotFoundException;
use App\Repository\LanguageRepository;
use App\ViewModel\LanguageDTO;

final class LanguageProvider implements LanguageProviderInterface
{
    private LanguageRepository $repository;

    public function __construct(LanguageRepository $languageRepository)
    {
        $this->repository = $languageRepository;
    }

    public function getItem(int $id): LanguageDTO
    {
        $item = $this->repository->find($id);

        if (null == $item) {
            throw new EntityNotFoundException('Language', $id);
        }

        return $item->getItem();
    }

    public function getList(): Languages
    {
        $items = $this->repository->findAll();

        $viewModels = \array_map(fn (Language $item) => $item->getItem(), $items);

        return new Languages(...$viewModels);
    }
}
