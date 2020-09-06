<?php

declare(strict_types=1);

namespace App\Service;

use App\Collection\WordGroups;
use App\ViewModel\WordGroupDTO;

interface WordGroupsProviderInterface
{
    public function getItem(int $id): WordGroupDTO;

    public function getList(): WordGroups;
}
