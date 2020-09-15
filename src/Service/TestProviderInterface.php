<?php

namespace App\Service;

use App\ViewModel\TestDTO;
use App\ViewModel\WordGroupDTO;

interface TestProviderInterface
{
    public function getTest(?WordGroupDTO $group = null): ?TestDTO;
}
