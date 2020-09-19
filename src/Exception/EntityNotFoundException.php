<?php

namespace App\Exception;

class EntityNotFoundException extends \RuntimeException
{
    public static function byId(string $entityName, int $id): self
    {
        $message = \sprintf('Entity "%s" with ID %d not found.', $entityName, $id);

        return new self($message);
    }

    public static function byName(string $entityName, string $name): self
    {
        $message = \sprintf('Entity "%s" with name "%s" not found.', $entityName, $name);

        return new self($message);
    }
}
