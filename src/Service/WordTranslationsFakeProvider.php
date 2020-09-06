<?php

declare(strict_types=1);

namespace App\Service;

use App\Collection\WordTranslations;
use App\ViewModel\WordTranslationDTO;
use Faker\Factory;
use Faker\Generator;

final class WordTranslationsFakeProvider
{
    private const ANSWERS_COUNT = 4;
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function getItem(int $id): WordTranslationDTO
    {
        return new WordTranslationDTO($id, $this->faker->words(
            $this->faker->numberBetween(1, 4),
            true
        ));
    }

    public function getList($wordId = null): WordTranslations
    {
        $answers = [];

        for ($i = 0; $i < self::ANSWERS_COUNT; ++$i) {
            $answers[] = $this->getItem($i + 1);
        }

        return new WordTranslations(...$answers);
    }
}
