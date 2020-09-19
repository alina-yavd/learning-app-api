<?php

namespace App\Service;

use App\Collection\Words;
use App\Exception\EntityNotFoundException;
use App\ViewModel\WordDTO;

/**
 * WordProviderInterface represents the interface for word provider implementations.
 */
interface WordProviderInterface
{
    /**
     * Finds one language by id.
     *
     * @return WordDTO Word view model
     *
     * @throws EntityNotFoundException
     */
    public function getItem(int $id): WordDTO;

    /**
     * Finds all words.
     *
     * @return Words Word view model collection
     */
    public function getList(): Words;
}
