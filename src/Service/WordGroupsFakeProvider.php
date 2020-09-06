<?php

declare(strict_types=1);

namespace App\Service;

use App\Collection\WordGroups;
use App\ViewModel\WordGroupDTO;
use Faker\Factory;
use Faker\Generator;

final class WordGroupsFakeProvider
{
    private const GROUPS_COUNT = 10;
    private Generator $faker;
    private WordFakeProvider $wordProvider;

    public function __construct()
    {
        $this->faker = Factory::create();
        $this->wordProvider = new WordFakeProvider();
    }

    public function getItem(int $id): WordGroupDTO
    {
        $words = $this->wordProvider->getList($this->faker->numberBetween(5, 30));
        $image = rand(0, 1) ? $this->faker->imageUrl(80, 100, 'abstract', true, 'Book Title') : null;

        return new WordGroupDTO($id, $this->faker->words(
            $this->faker->numberBetween(1, 2),
            true
        ), $words, $image);
    }

    public function getList(): WordGroups
    {
        $words = [];

        for ($i = 0; $i < self::GROUPS_COUNT; ++$i) {
            $words[] = $this->getItem($i + 1);
        }

        return new WordGroups(...$words);
    }
}
