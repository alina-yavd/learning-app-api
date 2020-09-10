<?php

declare(strict_types=1);

namespace App\Service\WordsImport;

use App\Entity\Language;
use App\Entity\Word;
use App\Entity\WordGroup;
use App\Entity\WordTranslation;
use Doctrine\ORM\EntityManagerInterface;

class WordsUploader implements WordsUploaderInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function upload(iterable $items, Language $originalLang, Language $translationLang, WordGroup $group = null): void
    {
        foreach ($items as $item) {
            $word = $this->em->getRepository('App\Entity\Word')->findOneBy(['text' => (string) $item->word]);

            if (null === $word) {
                $word = new Word();
                $word->setText((string) $item->word);
                $word->setCreatedAt(new \DateTimeImmutable());
                $word->setLanguage($originalLang);
            }

            $this->addWordToGroup($word, $group);
            $this->addWordTranslation($word, $item->translation, $translationLang);
            $this->em->persist($word);
        }

        $this->em->flush();
    }

    private function addWordToGroup($word, $group): void
    {
        if (null === $group) {
            return;
        }

        $word->addToGroup($group);
        $word->setUpdatedAt(new \DateTimeImmutable());
        $group->setUpdatedAt(new \DateTimeImmutable());
        $this->em->persist($group);
    }

    private function addWordTranslation($word, $translationText, $translationLang)
    {
        $translation = $this->em->getRepository('App\Entity\WordTranslation')->findOneBy(['text' => (string) $translationText]);

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
