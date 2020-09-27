<?php

namespace App\Service\WordsImport;

use App\Entity\Language;
use App\Entity\Word;
use App\Entity\WordGroup;
use App\Entity\WordTranslation;
use App\Repository\WordRepository;
use App\Repository\WordTranslationRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Implements WordsUploaderInterface for entities that are stored in database.
 */
final class WordsUploader implements WordsUploaderInterface
{
    private EntityManagerInterface $em;
    private WordRepository $wordRepository;
    private WordTranslationRepository $translationRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        WordRepository $wordRepository,
        WordTranslationRepository $translationRepository
    ) {
        $this->em = $entityManager;
        $this->wordRepository = $wordRepository;
        $this->translationRepository = $translationRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function upload(iterable $items, Language $originalLang, Language $translationLang, WordGroup $group = null): void
    {
        foreach ($items as $item) {
            $word = $this->wordRepository->findOneBy(['text' => (string) $item->word]);

            if (null === $word) {
                $word = new Word((string) $item->word, $originalLang);
                $word->setCreatedAt(new \DateTimeImmutable());
            }

            $this->addWordToGroup($word, $group);
            $this->addWordTranslation($word, $item->translation, $translationLang);
            $this->em->persist($word);
        }

        $this->em->flush();
    }

    private function addWordToGroup(Word $word, WordGroup $group): void
    {
        if (null === $group) {
            return;
        }

        $word->addToGroup($group);
        $word->setUpdatedAt(new \DateTimeImmutable());
        $group->setUpdatedAt(new \DateTimeImmutable());
        $this->em->persist($group);
    }

    private function addWordTranslation(Word $word, $translationText, $translationLang)
    {
        $translation = $this->translationRepository->findOneBy(['text' => (string) $translationText]);

        if (null === $translation) {
            $translation = new WordTranslation();
            $translation->setText((string) $translationText);
            $translation->setLanguage($translationLang);
            $translation->setCreatedAt(new \DateTimeImmutable());
            $this->em->persist($translation);
        } else {
            $translation->setUpdatedAt(new \DateTimeImmutable());
        }

        $word->addTranslation($translation);
        $word->setUpdatedAt(new \DateTimeImmutable());
    }
}
