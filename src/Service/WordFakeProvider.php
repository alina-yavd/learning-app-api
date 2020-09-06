<?php

declare(strict_types=1);

namespace App\Service;

use App\Collection\Words;
use App\ViewModel\WordDTO;
use App\ViewModel\WordGroupDTO;
use Faker\Factory;
use Faker\Generator;

final class WordFakeProvider
{
    private const WORDS_COUNT = 50;
    private Generator $faker;
    private WordTranslationsFakeProvider $translationsProvider;

    public function __construct()
    {
        $this->faker = Factory::create();
        $this->translationsProvider = new WordTranslationsFakeProvider();
    }

    public function getItem(int $id): WordDTO
    {
        $translations = $this->translationsProvider->getList();

        $groupId = rand(0, 1) ? $this->faker->numberBetween(1, 4) : null;
        $group = $groupId ? new WordGroupDTO($id, $this->faker->words(
            $this->faker->numberBetween(1, 2),
            true
        )) : null;

        return new WordDTO($id, $this->faker->words(
            $this->faker->numberBetween(1, 4),
            true
        ), $translations, $group);
    }

    public function getList(int $count = null): Words
    {
        $words = [];
        $count = $count ?? self::WORDS_COUNT;

        for ($i = 0; $i < $count; ++$i) {
            $words[] = $this->getItem($i + 1);
        }

        return new Words(...$words);
    }
}
