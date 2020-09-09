<?php

namespace App\Service\WordsImport;

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

    public function import($filePath, $originalLang, $translationLang, $group)
    {
        $this->uploader->upload($this->getData($filePath), $originalLang, $translationLang, $group);
    }
}
