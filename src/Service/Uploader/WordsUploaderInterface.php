<?php

declare(strict_types=1);

namespace App\Service\Uploader;

interface WordsUploaderInterface
{
    public function upload($items, $group = null): void;
}
