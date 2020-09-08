<?php

declare(strict_types=1);

namespace App\Service\Uploader;

use App\Entity\Language;
use App\Entity\Word;
use App\Entity\WordTranslation;
use App\Exception\UploadException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class WordsUploader implements WordsUploaderInterface
{
    private EntityManagerInterface $em;
    private Language $originalLang;
    private Language $translationLang;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function setLanguages(Language $originalLang, Language $translationLang)
    {
        $this->originalLang = $originalLang;
        $this->translationLang = $translationLang;
    }

    public function upload($items, $group = null): void
    {
        if (!$this->validateLang()) {
            throw new UploadException('Language not supported');
        }

        foreach ($items as $item) {
            $word = $this->em->getRepository('App\Entity\Word')->findOneBy(['text' => (string) $item->word]);

            if (null !== $word && null !== $group) {
                $word = $this->em->getRepository('App\Entity\Word')->findOneBy(['text' => (string) $item->word]);
                $word->addToGroup($group);
                $this->em->persist($group);

                continue;
            }

            $word = new Word();
            $word->setText((string) $item->word);
//            $word->setAdded(new DateTime()); // TODO: add word property
            $word->setLanguage($this->originalLang);

            $translation = new WordTranslation();
            $translation->setText((string) $item->translation);
            $translation->setLanguage($this->translationLang);
//            $translation->setAdded(new DateTime()); // TODO: add word translation property
            $word->addTranslation($translation);

            if (null !== $group) {
                $word->addToGroup($group);
                $this->em->persist($group);
            }

            $this->em->persist($translation);
            $this->em->persist($word);
        }

        $this->em->flush();
    }

    private function validateLang()
    {
        return null !== $this->originalLang && null !== $this->translationLang;
    }
}
