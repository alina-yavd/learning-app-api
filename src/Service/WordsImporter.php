<?php

namespace App\Service;

use App\Entity\Language;
use App\Entity\WordGroup;
use App\Exception\EntityNotFoundException;
use App\Exception\UploadException;
use App\Service\WordsImport\WordsImportFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Facade class for importing word lists.
 * Use the upload() method to import new words and their translations.
 */
final class WordsImporter
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

    /**
     * Imports the words and translations from the given file.
     * Uploaded files are not stored on the server.
     *
     * @param UploadedFile $file                File with words and translations in correct format.
     *                                          Look for WordsImportServiceInterface implementations to know which file types are supported.
     * @param string       $originalLangCode    original word language code
     * @param string       $translationLangCode translations language code
     * @param string|null  $groupName           (optional) Group name.
     *                                          If the group with given name exists, associates new words with existing group.
     *                                          If the group name is empty, words are imported, but not associated with any groups.
     */
    public function upload(
        UploadedFile $file,
        string $originalLangCode,
        string $translationLangCode,
        ?string $groupName = null
    ): void {
        $this->validateLang($originalLangCode, $translationLangCode);

        $filePath = $file->getRealPath();

        if (null !== $groupName) {
            $group = $this->getOrCreateGroup($groupName, $this->originalLang, $this->translationLang);
        } else {
            $group = null;
        }

        $strategy = $this->factory->getStrategy($file->getClientMimeType());
        $strategy->import($filePath, $this->originalLang, $this->translationLang, $group);
    }

    private function validateLang(string $originalLangCode, string $translationLangCode): void
    {
        $this->originalLang = $this->em->getRepository('App\Entity\Language')->findOneBy(['code' => $originalLangCode]);
        $this->translationLang = $this->em->getRepository('App\Entity\Language')->findOneBy(['code' => $translationLangCode]);

        if (null === $this->originalLang || null === $this->translationLang) {
            throw new UploadException('Languages not supported.');
        }
    }

    private function getOrCreateGroup(string $groupName, Language $language, Language $translation): WordGroup
    {
        try {
            $group = $this->groupProvider->getEntityByName((string) $groupName);
        } catch (EntityNotFoundException $e) {
            $group = null;
        }

        if (null === $group) {
            $group = $this->createGroup($groupName, $language, $translation);
        }

        return $group;
    }

    private function createGroup($groupName, $language, $translation): WordGroup
    {
        $group = new WordGroup();
        $group->setName($groupName);
        $group->setLanguage($language);
        $group->setTranslation($translation);
        $group->setCreatedAt(new \DateTimeImmutable());
        $this->em->getRepository('App\Entity\WordGroup')->create($group);

        return $group;
    }
}
