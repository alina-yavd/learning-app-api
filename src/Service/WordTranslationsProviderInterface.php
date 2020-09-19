<?php

namespace App\Service;

use App\Collection\WordTranslations;
use App\Exception\EntityNotFoundException;
use App\ViewModel\WordTranslationDTO;

/**
 * WordTranslationsProviderInterface represents the interface for word translation provider implementations.
 */
interface WordTranslationsProviderInterface
{
    /**
     * Finds one word translation by id.
     *
     * @return WordTranslationDTO Word translation view model
     *
     * @throws EntityNotFoundException
     */
    public function getItem(int $id): WordTranslationDTO;

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
     * Finds one random word translation for given word.
     *
     * @return WordTranslationDTO Word translation view model
     */
    public function getItemForWord(int $wordId): WordTranslationDTO;

    /**
     * Finds some word translations that are not associated with given word.
     *
     * @return WordTranslations Word translation view model collection
     */
    public function getListExcludingWord(int $wordId): WordTranslations;
}
