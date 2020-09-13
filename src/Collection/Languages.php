<?php

declare(strict_types=1);

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

    public function map(callable $fn)
    {
        $result = [];

        foreach ($this as $item) {
            $result[] = $fn($item);
        }

        return $result;
    }
}
