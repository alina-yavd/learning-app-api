<?php

namespace App\Transformer;

use App\ViewModel\WordTranslationDTO;
use League\Fractal\TransformerAbstract;

final class WordTranslationTransformer extends TransformerAbstract
{
    public function transform(WordTranslationDTO $translation): array
    {
        return [
            'id' => $translation->getId(),
            'text' => $translation->getText(),
        ];
    }
}
