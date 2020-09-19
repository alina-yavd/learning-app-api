<?php

namespace App\Service;

use App\ViewModel\WordDTO;
use App\ViewModel\WordGroupDTO;

/**
 * RandomWordProviderInterface represents the interface for random word provider implementations.
 */
interface RandomWordProviderInterface
{
    /**
     * Finds one random word.
     * If the group is given, finds one random word from this word group.
     *
     * @return WordDTO Word view model
     */
    public function getItem(?WordGroupDTO $group): WordDTO;
}
