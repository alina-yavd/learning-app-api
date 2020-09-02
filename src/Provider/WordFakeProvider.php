<?php

declare(strict_types=1);

namespace App\Provider;

use App\ViewModel\WordDTO;
use Faker\Factory;
use Faker\Generator;

final class WordFakeProvider implements WordProviderInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function getItem(int $id): WordDTO
    {
        return new WordDTO($id, $this->faker->words(
            $this->faker->numberBetween(1, 4),
            true
        ));
    }
}
