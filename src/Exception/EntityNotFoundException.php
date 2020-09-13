<?php

declare(strict_types=1);

namespace App\Exception;

class EntityNotFoundException extends \RuntimeException
{
    public function __construct(string $entityName, int $id)
    {
        $message = \sprintf('Entity "%s" with ID %d not found.', $entityName, $id);

        parent::__construct($message);
    }
}
