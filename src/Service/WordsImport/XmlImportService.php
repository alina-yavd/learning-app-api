<?php

namespace App\Service\WordsImport;

use SimpleXmlReader\SimpleXmlReader;

/**
 * Implements WordsImportServiceInterface for XML files.
 */
final class XmlImportService extends AbstractImportService implements WordsImportServiceInterface
{
    protected array $fileTypes = ['application/xml', 'text/xml'];

    /**
     * {@inheritdoc}
     */
    public function getData(string $filePath): ?iterable
    {
        $xml = SimpleXmlReader::openXML($filePath);

        return $xml->path('items/item') ?? null;
    }
}
