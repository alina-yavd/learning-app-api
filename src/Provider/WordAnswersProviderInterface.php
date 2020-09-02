<?php

declare(strict_types=1);

namespace App\Provider;

use App\Collection\WordAnswers;

interface WordAnswersProviderInterface
{
    public function getList(): WordAnswers;
}
