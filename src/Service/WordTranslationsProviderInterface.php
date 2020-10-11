<?php

namespace App\Service;

use App\Collection\WordTranslations;
use App\Exception\EntityNotFoundException;
use App\ViewModel\WordTranslationViewModel;

/**
 * WordTranslationsProviderInterface represents the interface for word translation provider implementations.
 */
interface WordTranslationsProviderInterface
{
    /**
     * Finds one word translation by id.
     *
     * @return WordTranslationViewModel Word translation view model
     *
     * @throws EntityNotFoundException
     */
    public function getItem(int $id): WordTranslationViewModel;

    /**
     * Finds all word translations.
     *
     * @return WordTranslations Word translation view model collection
     */
    public function getList(): WordTranslations;

    /**
     * Finds all word translations for given word.
     *
     * @return WordTranslations Word translation view model collection
     */
    public function getItemsForWord(int $wordId): WordTranslations;

    /**
     * Finds given number of word translations by filter.
     *
     * @return WordTranslations Word translation view model collection
     */
    public function getRandomList(int $count, WordFilter $filter): WordTranslations;
}
