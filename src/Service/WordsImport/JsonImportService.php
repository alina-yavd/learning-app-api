<?php

namespace App\Service\WordsImport;

/**
 * Implements WordsImportServiceInterface for JSON files.
 */
final class JsonImportService extends AbstractImportService implements WordsImportServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function getData(string $filePath): ?iterable
    {
        $json = \json_decode(\file_get_contents($filePath));

        return $json->items ?? null;
    }

    public static function getServiceKey(): string
    {
        return 'importer_json';
    }
}
