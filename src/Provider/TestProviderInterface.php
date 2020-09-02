<?php

declare(strict_types=1);

namespace App\Provider;

use App\ViewModel\TestDTO;

interface TestProviderInterface
{
    public function getTest(): TestDTO;
}
