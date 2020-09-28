<?php

namespace App\Service;

use App\Collection\WordGroups;
use App\Entity\WordGroup;
use App\Exception\EntityNotFoundException;
use App\ViewModel\WordGroupViewModel;

/**
 * WordGroupProviderInterface represents the interface for word group provider implementations.
 */
interface WordGroupProviderInterface
{
    /**
     * Finds one word group by id.
     *
     * @return WordGroupViewModel Word group view model
     *
     * @throws EntityNotFoundException
     */
    public function getItem(int $id): WordGroupViewModel;

    /**
     * Finds one word group by name.
     *
     * @return WordGroup Word group entity
     *
     * @throws EntityNotFoundException
     */
    public function getEntityByName(string $name): WordGroup;

    /**
     * Finds all word groups.
     *
     * @param WordGroupFilter $filter Contains filter data
     *
     * @return WordGroups Word group view model collection
     */
    public function getList(WordGroupFilter $filter): WordGroups;

    /**
     * Removes word group by id.
     */
    public function removeItem(int $id): void;

    /**
     * Removes word group by id, the words from this group are also deleted.
     * If the words are also associated with other word groups, they will not be deleted.
     */
    public function removeItemWithWords(int $id): void;
}
