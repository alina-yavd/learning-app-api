<?php

declare(strict_types=1);

namespace App\Service\Uploader\UploadStrategy;

use App\Entity\Language;
use App\Entity\WordGroup;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractUploadStrategy implements UploadStrategyInterface
{
    protected EntityManagerInterface $em;
    protected Language $originalLang;
    protected Language $translationLang;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function setLanguages(Language $originalLang, Language $translationLang)
    {
        $this->originalLang = $originalLang;
        $this->translationLang = $translationLang;
    }

    abstract public function upload(string $filePath, WordGroup $group = null);
}
