<?php

declare(strict_types=1);

namespace App\Provider;

use App\Collection\Words;
use App\ViewModel\WordDTO;
use App\ViewModel\WordGroupDTO;
use Faker\Factory;
use Faker\Generator;

final class WordFakeProvider implements WordProviderInterface
{
    private const WORDS_COUNT = 50;
    private Generator $faker;
    private WordAnswersFakeProvider $answersProvider;

    public function __construct()
    {
        $this->faker = Factory::create();
        $this->answersProvider = new WordAnswersFakeProvider();
    }

    public function getItem(int $id): WordDTO
    {
        $answers = $this->answersProvider->getList();

        $groupId = rand(0, 1) ? $this->faker->numberBetween(1, 4) : null;
        $group = $groupId ? new WordGroupDTO($id, $this->faker->words(
            $this->faker->numberBetween(1, 2),
            true
        )) : null;

        return new WordDTO($id, $this->faker->words(
            $this->faker->numberBetween(1, 4),
            true
        ), $answers, $group);
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
