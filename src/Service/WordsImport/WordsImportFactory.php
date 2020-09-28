<?php

namespace App\Service\WordsImport;

use Symfony\Component\DependencyInjection\ServiceLocator;

/**
 *  Implements WordsImportFactory for services found by ServiceLocator (app.import_services_tag).
 */
final class WordsImportFactory implements WordsImportFactoryInterface
{
    private ?ImportServicesCollection $services;

    public function __construct(ServiceLocator $locator, WordsUploaderInterface $uploader)
    {
        $services = [];
        $locatorServices = $locator->getProvidedServices();
        foreach ($locatorServices as $servicePath) {
            $services[] = new $servicePath($uploader);
        }
        $this->services = new ImportServicesCollection(...$services);
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $type): WordsImportServiceInterface
    {
        $strategies = $this->services->filter(function ($item) use ($type) {
            return in_array($type, $item->getSupportedTypes());
        });

        return array_shift($strategies);
    }
}
