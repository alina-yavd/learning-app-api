<?php

namespace App\Service\WordsImport;

use App\Entity\Language;
use App\Entity\Word;
use App\Entity\WordGroup;
use App\Repository\WordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

/**
 * Implements WordsUploaderInterface for entities that are stored in database.
 */
final class WordsUploader implements WordsUploaderInterface
{
    private EntityManagerInterface $em;
    private WordRepository $wordRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        WordRepository $wordRepository
    ) {
        $this->em = $entityManager;
        $this->wordRepository = $wordRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function upload(iterable $items, Language $originalLang, Language $translationLang, WordGroup $group = null): void
    {
        $this->em->getConnection()->beginTransaction();
        try {
            foreach ($items as $item) {
                $word = $this->wordRepository->findOneBy(['text' => (string) $item->word]);
                $translation = $this->wordRepository->findOneBy(['text' => (string) $item->translation]);

                if (null === $word) {
                    $word = new Word((string) $item->word, $originalLang);
                    $word->setCreatedAt(new \DateTimeImmutable());
                }

                if (null === $translation) {
                    $translation = new Word((string) $item->translation, $translationLang);
                    $translation->setCreatedAt(new \DateTimeImmutable());
                }

                $word->addTranslation($translation);
                $translation->addTranslationWord($word);
                $this->addWordToGroup($word, $group);
                $this->addWordToGroup($translation, $group);

                $this->em->persist($word);
                $this->em->persist($translation);
                $this->em->flush();
            }
            $this->em->getConnection()->commit();
        } catch (\Exception $e) {
            $this->em->getConnection()->rollBack();
            throw new UploadException($e->getMessage());
        }
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
}
