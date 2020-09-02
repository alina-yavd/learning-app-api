<?php

declare(strict_types=1);

namespace App\Provider;

use App\ViewModel\TestDTO;

final class TestFakeProvider implements TestProviderInterface
{
    private WordProviderInterface $wordProvider;
    private WordAnswersProviderInterface $answersProvider;

    public function __construct()
    {
        $this->wordProvider = new WordFakeProvider();
        $this->answersProvider = new WordAnswersFakeProvider();
    }

    public function getTest(): TestDTO
    {
        $id = \random_int(1, 10);
        $word = $this->wordProvider->getItem($id);
        $answers = $this->answersProvider->getList();

        return new TestDTO($word, $answers);
    }
}
