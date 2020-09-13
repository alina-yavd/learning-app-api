<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Language;
use App\Entity\WordGroup;
use App\Exception\UploadException;
use App\Service\WordsImport\WordsImportFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class WordsImporter
{
    private WordsImportFactory $factory;
    private EntityManagerInterface $em;
    private WordGroupProviderInterface $groupProvider;
    private ?Language $originalLang;
    private ?Language $translationLang;

    public function __construct(
        WordsImportFactory $factory,
        EntityManagerInterface $em,
        WordGroupProviderInterface $groupProvider
    ) {
        $this->factory = $factory;
        $this->em = $em;
        $this->groupProvider = $groupProvider;
    }

    public function upload(
        UploadedFile $file,
        string $originalLangCode,
        string $translationLangCode,
        string $groupName = null
    ): void {
        $this->validateLang($originalLangCode, $translationLangCode);

        $filePath = $file->getRealPath();
        $group = $this->getOrCreateGroup($groupName, $this->originalLang, $this->translationLang);

        $strategy = $this->factory->getStrategy($file->getClientMimeType());
        $strategy->import($filePath, $this->originalLang, $this->translationLang, $group);
    }

    private function validateLang($originalLangCode, $translationLangCode): void
    {
        $this->originalLang = $this->em->getRepository('App\Entity\Language')->findOneBy(['code' => $originalLangCode]);
        $this->translationLang = $this->em->getRepository('App\Entity\Language')->findOneBy(['code' => $translationLangCode]);

        if (null === $this->originalLang || null === $this->translationLang) {
            throw new UploadException('Languages not supported.');
        }
    }

    private function getOrCreateGroup(string $groupName, Language $language, Language $translation)
    {
        if (null !== $groupName) {
            $group = $this->groupProvider->getItemByName((string) $groupName);

            if (null === $group) {
                $group = new WordGroup();
                $group->setName((string) $groupName);
                $group->setLanguage($language);
                $group->setTranslation($translation);
                $group->setCreatedAt(new \DateTimeImmutable());
                $this->em->persist($group);
                $this->em->flush();
            }
        }

        // $this->uploadGroupImage();

        return $group ?? null;
    }
}
