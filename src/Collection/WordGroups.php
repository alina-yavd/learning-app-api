<?php

namespace App\Collection;

use App\ViewModel\WordGroupViewModel;

final class WordGroups implements \IteratorAggregate
{
    private array $groups;

    public function __construct(WordGroupViewModel ...$groups)
    {
        $this->groups = $groups;
    }

    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->groups);
    }

    public function map(callable $fn)
    {
        $result = [];

        foreach ($this as $item) {
            $result[] = $fn($item);
        }

        return $result;
    }

    public function add(WordGroupViewModel $translation)
    {
        return array_unshift($this->groups, $translation);
    }

    public function contains($value, $strict = true)
    {
        return in_array($value, $this->groups, $strict);
    }
}
