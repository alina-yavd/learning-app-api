<?php

declare(strict_types=1);

namespace App\Provider;

use App\Collection\WordAnswers;
use App\ViewModel\WordAnswerDTO;
use Faker\Factory;
use Faker\Generator;

final class WordAnswersFakeProvider implements WordAnswersProviderInterface
{
    private const ANSWERS_COUNT = 3;
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function getList(): WordAnswers
    {
        $answers = [];

        for ($i = 0; $i < self::ANSWERS_COUNT; ++$i) {
            $answers[] = $this->createAnswer($i + 1);
        }

        return new WordAnswers(...$answers);
    }

    private function createAnswer(int $id): WordAnswerDTO
    {
        return new WordAnswerDTO($id, $this->faker->words(
            $this->faker->numberBetween(1, 4),
            true
        ));
    }
}
