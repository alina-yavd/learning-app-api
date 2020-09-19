<?php

namespace App\Service\WordsImport;

/**
 * WordsImportFactoryInterface represents the interface for words import factory implementations.
 */
interface WordsImportFactoryInterface
{
    /**
     * Finds the appropriate strategy depending on uploaded file type.
     */
    public function getStrategy(string $type): WordsImportServiceInterface;
}
