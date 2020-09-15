<?php

namespace App\Service\WordsImport;

use App\Entity\Language;
use App\Entity\WordGroup;

abstract class AbstractImportService implements WordsImportServiceInterface
{
    private WordsUploaderInterface $uploader;

    public function __construct(WordsUploaderInterface $uploader)
    {
        $this->uploader = $uploader;
    }

    public function getSupportedTypes(): array
    {
        return $this->fileTypes;
    }

    abstract public function getData(string $filePath): ?iterable;

    public function import(string $filePath, Language $originalLang, Language $translationLang, WordGroup $group)
    {
        $this->uploader->upload($this->getData($filePath), $originalLang, $translationLang, $group);
    }
}
