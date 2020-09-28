<?php

namespace App\Transformer;

use App\ViewModel\WordViewModel;
use League\Fractal\TransformerAbstract;

final class WordTransformer extends TransformerAbstract
{
    public function transform(WordViewModel $word): array
    {
        return [
            'id' => $word->getId(),
            'text' => $word->getText(),
            'translations' => $word->getTranslations()->toArray(),
        ];
    }
}
