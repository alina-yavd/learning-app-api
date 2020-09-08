<?php

declare(strict_types=1);

namespace App\Service\Uploader\UploadStrategy;

use App\Entity\WordGroup;

interface UploadStrategyInterface
{
    public function upload(string $filePath, WordGroup $group = null);
}
