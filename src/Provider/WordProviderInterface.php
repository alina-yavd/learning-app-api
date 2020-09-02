<?php

declare(strict_types=1);

namespace App\Provider;

use App\Collection\Words;
use App\ViewModel\WordDTO;

interface WordProviderInterface
{
    public function getItem(int $id): WordDTO;

    public function getList(): Words;
}
