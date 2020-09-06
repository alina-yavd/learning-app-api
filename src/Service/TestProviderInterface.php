<?php

declare(strict_types=1);

namespace App\Service;

use App\ViewModel\TestDTO;

interface TestProviderInterface
{
    public function getTest(): ?TestDTO;
}
