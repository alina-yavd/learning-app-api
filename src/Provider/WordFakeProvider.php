<?php

declare(strict_types=1);

namespace App\Provider;

use App\Collection\WordAnswers;
use App\ViewModel\WordDTO;
use Faker\Factory;
use Faker\Generator;

final class WordFakeProvider implements WordProviderInterface
{
    private Generator $faker;
    private WordAnswersFakeProvider $answersProvider;

    public function __construct()
    {
        $this->faker = Factory::create();
        $this->answersProvider = new WordAnswersFakeProvider();
    }

    public function getItem(int $id): WordDTO
    {
        return new WordDTO($id, $this->faker->words(
            $this->faker->numberBetween(1, 4),
            true
        ));
    }

    public function getAnswers(int $id)
    {
        $answers[] = $this->answersProvider->createAnswer($id);
        for ($i = 1; $i < $this->faker->numberBetween(1, 3); ++$i) {
            $answers[] = $this->answersProvider->createAnswer($i + 1);
        }

        return new WordAnswers(...$answers);
    }
}
