<?php

namespace App\Collection;

use App\ViewModel\LanguageDTO;

final class Languages implements \IteratorAggregate
{
    private array $languages;

    public function __construct(LanguageDTO ...$languages)
    {
        $this->languages = $languages;
    }

    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->languages);
    }
}
