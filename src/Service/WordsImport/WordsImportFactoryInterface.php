<?php

namespace App\Service\WordsImport;

interface WordsImportFactoryInterface
{
    public function getStrategy(string $type): WordsImportServiceInterface;
}
