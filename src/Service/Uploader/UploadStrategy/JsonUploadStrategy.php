<?php

declare(strict_types=1);

namespace App\Service\Uploader\UploadStrategy;

use App\Entity\WordGroup;
use App\Service\Uploader\WordsUploader;

class JsonUploadStrategy extends AbstractUploadStrategy implements UploadStrategyInterface
{
    public function upload(string $filePath, WordGroup $group = null): void
    {
        $json = \json_decode(\file_get_contents($filePath));
        $words = $json->items ?? null;

        $uploader = new WordsUploader($this->em);
        $uploader->setLanguages($this->originalLang, $this->translationLang);
        $uploader->upload($words, $group);
    }
}
