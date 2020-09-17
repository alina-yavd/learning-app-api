<?php

namespace App\Transformer;

use App\ViewModel\TestDTO;
use App\ViewModel\WordTranslationDTO;
use League\Fractal\TransformerAbstract;

class TestTransformer extends TransformerAbstract
{
    public function transform(TestDTO $test): array
    {
        $data = [
            'word' => [
                'id' => $test->getWord()->getId(),
                'text' => $test->getWord()->getText(),
            ],
            'answers' => $test->getAnswers()->map(function (WordTranslationDTO $item) {
                return [
                    'id' => $item->getId(),
                    'text' => $item->getText(),
                ];
            }),
            'group' => null,
        ];

        if (null !== $test->getGroup()) {
            $data['group'] = [
                'id' => $test->getGroup()->getId(),
                'name' => $test->getGroup()->getName(),
            ];
        }

        return $data;
    }
}
