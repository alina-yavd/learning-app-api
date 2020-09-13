<?php

declare(strict_types=1);

namespace App\Service\WordsImport;

final class ImportServicesCollection implements \IteratorAggregate
{
    private array $services;

    public function __construct(...$services)
    {
        $this->services = $services;
    }

    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->services);
    }

    public function map(callable $fn)
    {
        $result = [];

        foreach ($this as $item) {
            $result[] = $fn($item);
        }

        return $result;
    }

    public function filter(callable $fn)
    {
        return array_filter($this->services, $fn);
    }
}
