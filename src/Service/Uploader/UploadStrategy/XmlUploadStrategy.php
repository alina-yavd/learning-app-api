<?php

declare(strict_types=1);

namespace App\Service\Uploader\UploadStrategy;

use App\Entity\WordGroup;
use App\Service\Uploader\WordsUploader;
use SimpleXmlReader\SimpleXmlReader;

class XmlUploadStrategy extends AbstractUploadStrategy implements UploadStrategyInterface
{
    public function upload(string $filePath, WordGroup $group = null): void
    {
        $xml = SimpleXmlReader::openXML($filePath);

        $uploader = new WordsUploader($this->em);
        $uploader->setLanguages($this->originalLang, $this->translationLang);
        $uploader->upload($xml->path('items/item'), $group);
    }
}
