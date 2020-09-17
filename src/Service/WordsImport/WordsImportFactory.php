<?php

namespace App\Service\WordsImport;

use Symfony\Component\DependencyInjection\ServiceLocator;

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

    public function getStrategy(string $type): WordsImportServiceInterface
    {
        $strategies = $this->services->filter(function ($item) use ($type) {
            return in_array($type, $item->getSupportedTypes());
        });

        return array_shift($strategies);
    }
}
