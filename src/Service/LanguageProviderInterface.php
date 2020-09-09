<?php

declare(strict_types=1);

namespace App\Service;

use App\Collection\Languages;
use App\ViewModel\LanguageDTO;

interface LanguageProviderInterface
{
    public function getItem(int $id): LanguageDTO;

    public function getList(): Languages;
}