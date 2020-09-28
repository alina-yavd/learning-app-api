<?php

namespace App\Service;

use App\Collection\Words;
use App\Exception\EntityNotFoundException;
use App\ViewModel\WordViewModel;

/**
 * WordProviderInterface represents the interface for word provider implementations.
 */
interface WordProviderInterface
{
    /**
     * Finds one language by id.
     *
     * @return WordViewModel Word view model
     *
     * @throws EntityNotFoundException
     */
    public function getItem(int $id): WordViewModel;

    /**
     * Finds all words.
     *
     * @return Words Word view model collection
     */
    public function getList(): Words;
}
