<?php

namespace App\Service;

use App\Collection\Languages;
use App\Exception\EntityNotFoundException;
use App\Exception\LanguageCreateException;
use App\ViewModel\LanguageDTO;

/**
 * LanguageProviderInterface represents the interface for language provider implementations.
 */
interface LanguageProviderInterface
{
    /**
     * Finds one language by id.
     *
     * @return LanguageDTO Language view model
     *
     * @throws EntityNotFoundException
     */
    public function getItem(int $id): LanguageDTO;

    /**
     * Finds all languages.
     *
     * @return Languages Language view model collection
     */
    public function getList(): Languages;

    /**
     * Creates new language entity.
     *
     * @return LanguageDTO Language view model
     *
     * @throws LanguageCreateException
     */
    public function createItem(string $code, string $name): LanguageDTO;
}
