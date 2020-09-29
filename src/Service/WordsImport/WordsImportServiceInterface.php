<?php

namespace App\Service\WordsImport;

use App\Entity\Language;
use App\Entity\WordGroup;

/**
 * WordsImportServiceInterface represents the interface for word import services implementations.
 */
interface WordsImportServiceInterface
{
    /**
     * Imports words and translations from the file.
     *
     * @param string    $filePath        path to the file with words and translations
     * @param Language  $originalLang    original word's language
     * @param Language  $translationLang translations word's language
     * @param WordGroup $group           word group for imported words list
     *
     * @return void
     */
    public function import(string $filePath, Language $originalLang, Language $translationLang, WordGroup $group);

    /**
     * Sets the key for service locator.
     *
     * Returns string with file type with 'importer_' prefix.
     */
    public static function getServiceKey(): string;
}
