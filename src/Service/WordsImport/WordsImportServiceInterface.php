<?php

namespace App\Service\WordsImport;

interface WordsImportServiceInterface
{
    public function getSupportedTypes(): array;

    public function getData(string $filePath): ?iterable;

    public function import($filePath, $originalLang, $translationLang, $group);
}
