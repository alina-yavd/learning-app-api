<?php

namespace App\Service;

use App\Collection\WordGroups;
use App\Entity\WordGroup;
use App\Exception\EntityNotFoundException;
use App\ViewModel\WordGroupDTO;

/**
 * WordGroupProviderInterface represents the interface for word group provider implementations.
 */
interface WordGroupProviderInterface
{
    /**
     * Finds one word group by id.
     *
     * @return WordGroupDTO Word group view model
     *
     * @throws EntityNotFoundException
     */
    public function getItem(int $id): WordGroupDTO;

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
     * @param array $filter Contains associated array with the filter data
     *
     * @return WordGroups Word group view model collection
     */
    public function getList(array $filter = []): WordGroups;

    /**
     * Removes word group by id.
     *
     * @param bool $deleteWithData If true, the words from this group are also deleted.
     *                             If the words are also associated with other word groups, they will not be deleted.
     */
    public function removeItem(int $id, $deleteWithData = false): void;
}
