<?php

declare(strict_types=1);

namespace App\Provider;

use App\Collection\WordAnswers;
use App\ViewModel\WordAnswerDTO;

interface WordAnswersProviderInterface
{
    public function getItem(int $id): WordAnswerDTO;

    public function getList(): WordAnswers;
}
