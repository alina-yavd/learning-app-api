<?php

namespace App\Service\WordsImport;

use App\Entity\Language;
use App\Entity\WordGroup;

interface WordsUploaderInterface
{
    public function upload(iterable $items, Language $originalLang, Language $translationLang, WordGroup $group = null): void;
}
