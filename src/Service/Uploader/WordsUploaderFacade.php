<?php

declare(strict_types=1);

namespace App\Service\Uploader;

use App\Entity\Language;
use App\Entity\WordGroup;
use App\Exception\UploadException;
use App\Service\Uploader\UploadStrategy\JsonUploadStrategy;
use App\Service\Uploader\UploadStrategy\UploadStrategy;
use App\Service\Uploader\UploadStrategy\XmlUploadStrategy;
use App\Service\WordGroupProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class WordsUploaderFacade
{
    private ParameterBagInterface $params;
    private SluggerInterface $slugger;
    private EntityManagerInterface $em;
    private WordGroupProviderInterface $groupProvider;
    private string $uploadDirectory = 'word-lists';
    private array $supportedFileTypes = [
        'application/xml',
        'text/xml',
        'application/json',
    ];
    private ?string $originalLangCode = null;
    private ?string $translationLangCode = null;
    private ?Language $originalLang;
    private ?Language $translationLang;

    public function __construct(
        SluggerInterface $slugger,
        ParameterBagInterface $params,
        EntityManagerInterface $em,
        WordGroupProviderInterface $groupProvider
    ) {
        $this->params = $params;
        $this->slugger = $slugger;
        $this->em = $em;
        $this->groupProvider = $groupProvider;
    }

    public function setLanguages(string $originalLangCode, string $translationLangCode): void
    {
        $this->originalLangCode = $originalLangCode;
        $this->translationLangCode = $translationLangCode;
    }

    public function upload(UploadedFile $file, string $groupName = null): void
    {
        $this->validateLang();
        $this->validateFile($file);

        $filePath = $file->getRealPath();
        $group = $this->getOrCreateGroup($groupName);

        $uploader = $this->getUploader($file->getClientMimeType());
        $uploader->setLanguages($this->originalLang, $this->translationLang);
        $uploader->upload($filePath, $group);
    }

    private function validateLang(): void
    {
        if (null === $this->originalLangCode || null === $this->translationLangCode) {
            throw new UploadException('Set original and translation languages first.');
        }

        $this->originalLang = $this->em->getRepository('App\Entity\Language')->findOneBy(['code' => $this->originalLangCode]);
        $this->translationLang = $this->em->getRepository('App\Entity\Language')->findOneBy(['code' => $this->translationLangCode]);

        if (null === $this->originalLang || null === $this->translationLang) {
            throw new UploadException('Languages not supported.');
        }
    }

    private function validateFile($file): void
    {
        // TODO: Check filesize

        if (false === \array_search(
                $file->getClientMimeType(),
                $this->supportedFileTypes,
                true
            )) {
            throw new UploadException('Invalid file format.');
        }
    }

    private function getUploader($fileType): UploadStrategy
    {
        switch ($fileType) {
            case 'application/xml':
            case 'text/xml':
                $uploader = new UploadStrategy(new XmlUploadStrategy($this->em));

                break;
            case 'application/json':
                $uploader = new UploadStrategy(new JsonUploadStrategy($this->em));

                break;
            default:
                throw new UploadException('Invalid file format.');
        }

        return $uploader;
    }

    private function getOrCreateGroup($groupName)
    {
        if (null !== $groupName) {
            $group = $this->groupProvider->getItemByName((string) $groupName);

            if (null === $group) {
                $group = new WordGroup();
                $group->setName((string) $groupName);
                $this->em->persist($group);
                $this->em->flush();
            }
        }

        // $this->uploadGroupImage();

        return $group ?? null;
    }

    // TODO: add group image uploading logic
    private function uploadGroupImage(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = time().'_'.$safeFilename.'_'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move(
                $this->uploadDirectory,
                $fileName
            );
        } catch (FileException $e) {
            throw new UploadException($e->getMessage());
        }

        return $fileName;
    }
}
