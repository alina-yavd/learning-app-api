<?php

namespace App\Service\WordsImport;

use SimpleXmlReader\SimpleXmlReader;

/**
 * Implements WordsImportServiceInterface for XML files.
 */
final class XmlImportService extends AbstractImportService implements WordsImportServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function getData(string $filePath): ?iterable
    {
        $xml = SimpleXmlReader::openXML($filePath);

        return $xml->path('items/item') ?? null;
    }

    public static function getServiceKey(): string
    {
        return 'importer_xml';
    }
}
