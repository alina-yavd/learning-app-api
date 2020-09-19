<?php

namespace App\Collection;

use App\ViewModel\WordTranslationDTO;

final class WordTranslations implements \IteratorAggregate
{
    private array $translations;

    public function __construct(WordTranslationDTO ...$translations)
    {
        $this->translations = $translations;
    }

    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->translations);
    }

    public function map(callable $fn)
    {
        $result = [];

        foreach ($this as $item) {
            $result[] = $fn($item);
        }

        return $result;
    }

    public function add(WordTranslationDTO $translation)
    {
        return array_unshift($this->translations, $translation);
    }

    public function contains($value, $strict = false)
    {
        return in_array($value, $this->translations, $strict);
    }

    public function shuffle()
    {
        \shuffle($this->translations);

        return $this->translations;
    }
}
