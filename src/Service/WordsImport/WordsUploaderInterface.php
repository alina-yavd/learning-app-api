<?php

namespace App\Service\WordsImport;

use App\Entity\Language;
use App\Entity\WordGroup;

/**
 * WordsUploaderInterface represents the interface for word uploader service implementations.
 */
interface WordsUploaderInterface
{
    /**
     * Uploads words and translations to the database.
     *
     * @param iterable $items array or collection of words and their translations
     */
    public function upload(iterable $items, Language $originalLang, Language $translationLang, WordGroup $group = null): void;
}
