<?php

namespace App\Service;

use App\Collection\Languages;
use App\Exception\LanguageAlreadyExistsException;
use App\ViewModel\LanguageViewModel;
use InvalidArgumentException;

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
     * @return LanguageViewModel Language view model
     *
     * @throws LanguageAlreadyExistsException | InvalidArgumentException
     */
    public function createItem(string $code, string $name): LanguageViewModel;
}
