<?php

namespace App\Service\WordsImport;

class JsonImportService extends AbstractImportService implements WordsImportServiceInterface
{
    protected array $fileTypes = ['application/json'];

    public function getData(string $filePath): ?iterable
    {
        $json = \json_decode(\file_get_contents($filePath));

        return $json->items ?? null;
    }
}
