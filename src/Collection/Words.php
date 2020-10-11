<?php

namespace App\Collection;

use App\ViewModel\WordViewModel;

final class Words implements \IteratorAggregate
{
    private array $words;

    public function __construct(WordViewModel ...$words)
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

    public function add(WordViewModel $translation)
    {
        return array_unshift($this->words, $translation);
    }

    public function contains($value, $strict = true)
    {
        return in_array($value, $this->words, $strict);
    }

    public function shuffle()
    {
        \shuffle($this->words);

        return $this->words;
    }
}
