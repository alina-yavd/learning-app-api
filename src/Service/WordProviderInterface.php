<?php

namespace App\Service;

use App\Collection\Words;
use App\ViewModel\WordDTO;

interface WordProviderInterface
{
    public function getItem(int $id): WordDTO;

    public function getList(): Words;
}
