<?php

namespace App\Service;

use App\Collection\WordTranslations;
use App\ViewModel\WordTranslationDTO;

interface WordTranslationsProviderInterface
{
    public function getItem(int $id): WordTranslationDTO;

    public function getList(): WordTranslations;
}
