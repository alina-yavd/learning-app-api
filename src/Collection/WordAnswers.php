<?php

declare(strict_types=1);

namespace App\Collection;

use App\ViewModel\WordAnswerDTO;

final class WordAnswers implements \IteratorAggregate
{
    private array $answers;

    public function __construct(WordAnswerDTO ...$answers)
    {
        $this->answers = $answers;
    }

    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->answers);
    }

    public function map(callable $fn)
    {
        $result = [];

        foreach ($this as $item) {
            $result[] = $fn($item);
        }

        return $result;
    }
}
