<?php

namespace App\Service\WordsImport;

use App\Exception\UploadException;
use Symfony\Component\DependencyInjection\ServiceLocator;

/**
 *  Implements WordsImportFactory for services found by ServiceLocator (app.import_services).
 */
final class WordsImportFactory implements WordsImportFactoryInterface
{
    private ServiceLocator $locator;

    public function __construct(ServiceLocator $locator)
    {
        $this->locator = $locator;
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $type): WordsImportServiceInterface
    {
        if ($this->locator->has('importer_'.$type)) {
            return $this->locator->get('importer_'.$type);
        } else {
            throw new UploadException('Uploaded file type not supported.');
        }
    }
}
