<?php

namespace App\Collection;

use App\ViewModel\LanguageViewModel;

final class Languages implements \IteratorAggregate
{
    private array $languages;

    public function __construct(LanguageViewModel ...$languages)
    {
        $this->languages = $languages;
    }

    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->languages);
    }
}
