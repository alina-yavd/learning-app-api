<?php

namespace App\Service;

use App\ViewModel\WordGroupViewModel;
use App\ViewModel\WordViewModel;

/**
 * RandomWordProviderInterface represents the interface for random word provider implementations.
 */
interface RandomWordProviderInterface
{
    /**
     * Finds one random word.
     * If the group is given, finds one random word from this word group.
     *
     * @return WordViewModel Word view model
     */
    public function getItem(?WordGroupViewModel $group): WordViewModel;
}
