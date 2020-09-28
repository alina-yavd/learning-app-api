<?php

namespace App\Service;

use App\Entity\Language;
use App\Entity\WordGroup;
use App\Exception\EntityNotFoundException;
use App\Exception\UploadException;
use App\Repository\LanguageRepository;
use App\Repository\WordGroupRepository;
use App\Service\WordsImport\WordsImportFactory;
use App\DTO\UploadedWordListDTO;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Facade class for importing word lists.
 * Use the upload() method to import new words and their translations.
 */
final class WordsImporter
{
    private WordsImportFactory $factory;
    private EntityManagerInterface $em;
    private WordGroupProviderInterface $groupProvider;
    private LanguageRepository $languageRepository;
    private WordGroupRepository $groupRepository;
    private ?Language $originalLang;
    private ?Language $translationLang;

    public function __construct(
        WordsImportFactory $factory,
        EntityManagerInterface $em,
        WordGroupProviderInterface $groupProvider,
        LanguageRepository $languageRepository,
        WordGroupRepository $groupRepository
    ) {
        $this->factory = $factory;
        $this->em = $em;
        $this->groupProvider = $groupProvider;
        $this->languageRepository = $languageRepository;
        $this->groupRepository = $groupRepository;
    }

    /**
     * Imports the words and translations from the given file.
     * Uploaded files are not stored on the server.
     *
     * Look for WordsImportServiceInterface implementations to know which file types are supported.
     *
     * If the group with given name exists, associates new words with existing group.
     * If the group name is empty, words are imported, but not associated with any groups.
     *
     * @param UploadedWordListDTO $wordList Word list object with the required data
     */
    public function upload(UploadedWordListDTO $wordList): void
    {
        $this->validateLang($wordList->getOriginalCode(), $wordList->getTranslationCode());

        $file = $wordList->getFile();
        $filePath = $file->getRealPath();

        if (null !== $wordList->getGroupName()) {
            $group = $this->getOrCreateGroup($wordList->getGroupName(), $this->originalLang, $this->translationLang);
        } else {
            $group = null;
        }

        $strategy = $this->factory->create($file->getClientMimeType());
        $strategy->import($filePath, $this->originalLang, $this->translationLang, $group);
    }

    private function validateLang(string $originalLangCode, string $translationLangCode): void
    {
        $this->originalLang = $this->languageRepository->findOneBy(['code' => $originalLangCode]);
        $this->translationLang = $this->languageRepository->findOneBy(['code' => $translationLangCode]);

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
        $this->groupRepository->create($group);

        return $group;
    }
}
