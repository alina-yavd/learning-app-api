<?php

namespace App\Service\WordsImport;

use App\Entity\Language;
use App\Entity\WordGroup;

interface WordsImportServiceInterface
{
    public function getSupportedTypes(): array;

    public function getData(string $filePath): ?iterable;

    public function import(string $filePath, Language $originalLang, Language $translationLang, WordGroup $group);
}
