<?php

namespace App\Service\WordsImport;

use App\Entity\Language;
use App\Entity\WordGroup;

/**
 * AbstractImportService implements general methods for words import services.
 */
abstract class AbstractImportService implements WordsImportServiceInterface
{
    private WordsUploaderInterface $uploader;

    public function __construct(WordsUploaderInterface $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * Gets the words and translations data from the file.
     */
    abstract public function getData(string $filePath): ?iterable;

    /**
     * {@inheritdoc}
     */
    public function import(string $filePath, Language $originalLang, Language $translationLang, WordGroup $group)
    {
        $this->uploader->upload($this->getData($filePath), $originalLang, $translationLang, $group);
    }
}
