<?php

namespace App\Service;

use App\ViewModel\TestViewModel;

/**
 * TestProviderInterface represents the interface for word tests implementations.
 */
interface TestProviderInterface
{
    /**
     * Generates the test that includes random word and some possible word answers.
     * If the group is given, random word for test will be found in this word group.
     *
     * @return ?TestViewModel Test view model
     */
    public function getTest(?int $groupId = null): ?TestViewModel;

    /**
     * Checks if the given answer belongs to the given word.
     */
    public function checkAnswer(int $wordId, int $answerId): bool;
}
