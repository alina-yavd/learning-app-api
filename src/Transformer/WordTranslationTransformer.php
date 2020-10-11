<?php

namespace App\Transformer;

use App\ViewModel\WordTranslationViewModel;
use League\Fractal\TransformerAbstract;

final class WordTranslationTransformer extends TransformerAbstract
{
    public function transform(WordTranslationViewModel $translation): array
    {
        return [
            'id' => $translation->getId(),
            'text' => $translation->getText(),
            'language' => $translation->getLanguage()->getCode(),
        ];
    }
}
