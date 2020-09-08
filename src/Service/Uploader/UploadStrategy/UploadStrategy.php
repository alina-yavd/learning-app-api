<?php

declare(strict_types=1);

namespace App\Service\Uploader\UploadStrategy;

use App\Entity\Language;
use App\Entity\WordGroup;

class UploadStrategy
{
    private UploadStrategyInterface $service;

    public function __construct(UploadStrategyInterface $service)
    {
        $this->service = $service;
    }

    public function setStrategy(UploadStrategyInterface $service): void
    {
        $this->service = $service;
    }

    public function upload(string $filePath, WordGroup $group = null)
    {
        return $this->service->upload($filePath, $group);
    }

    public function setLanguages(Language $originalLangCode, Language $translationLangCode): void
    {
        $this->service->setLanguages($originalLangCode, $translationLangCode);
    }
}
