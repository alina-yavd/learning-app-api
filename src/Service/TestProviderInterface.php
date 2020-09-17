<?php

namespace App\Service;

use App\ViewModel\TestDTO;

interface TestProviderInterface
{
    public function getTest(?int $groupId = null): ?TestDTO;
}
