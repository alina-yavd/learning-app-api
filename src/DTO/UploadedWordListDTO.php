<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class UploadedWordListDTO
{
    /**
     * @Assert\NotBlank
     */
    private ?string $originalCode;

    /**
     * @Assert\NotBlank
     */
    private ?string $translationCode;

    /**
     * @Assert\File(maxSize = "2048k")
     */
    private ?UploadedFile $file;

    private ?string $groupName;

    public function __construct($request, $files)
    {
        $this->originalCode = $request->get('original');
        $this->translationCode = $request->get('translation');
        $this->file = $files->get('file');
        $this->groupName = $request->get('group');
    }

    public function getOriginalCode(): ?string
    {
        return $this->originalCode;
    }

    public function getTranslationCode(): ?string
    {
        return $this->translationCode;
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }
}
