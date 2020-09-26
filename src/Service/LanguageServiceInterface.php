<?php

namespace App\Service;

use App\Collection\Languages;
use App\Exception\LanguageAlreadyExistsException;
use App\ViewModel\LanguageDTO;

/**
 * LanguageServiceInterface represents the interface for language service implementations.
 */
interface LanguageServiceInterface
{
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
     * @throws LanguageAlreadyExistsException
     */
    public function createItem(string $code, string $name): LanguageDTO;
}
