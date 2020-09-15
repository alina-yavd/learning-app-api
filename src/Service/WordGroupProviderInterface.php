<?php

namespace App\Service;

use App\Collection\WordGroups;
use App\ViewModel\WordGroupDTO;

interface WordGroupProviderInterface
{
    public function getItem(int $id): WordGroupDTO;

    public function getList(array $filter = []): WordGroups;
}
