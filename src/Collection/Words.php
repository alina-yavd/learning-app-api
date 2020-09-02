<?php

declare(strict_types=1);

namespace App\Collection;

use App\ViewModel\WordDTO;

final class Words implements \IteratorAggregate
{
    private array $words;

    public function __construct(WordDTO ...$words)
    {
        $this->words = $words;
    }

    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->words);
    }

    public function map(callable $fn)
    {
        $result = [];

        foreach ($this as $item) {
            $result[] = $fn($item);
        }

        return $result;
    }

    public function add(WordDTO $answer)
    {
        return array_unshift($this->words, $answer);
    }

    public function contains($value, $strict = true)
    {
        return in_array($value, $this->words, $strict);
    }
}
