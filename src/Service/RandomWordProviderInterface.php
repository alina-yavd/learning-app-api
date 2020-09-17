<?php

namespace App\Service;

use App\ViewModel\WordDTO;
use App\ViewModel\WordGroupDTO;

interface RandomWordProviderInterface
{
    public function getItem(?WordGroupDTO $group): WordDTO;
}
