<?php

namespace App\Transformer;

use App\ViewModel\WordDTO;
use League\Fractal\TransformerAbstract;

final class WordTransformer extends TransformerAbstract
{
    public function transform(WordDTO $word): array
    {
        return [
            'id' => $word->getId(),
            'text' => $word->getText(),
            'translations' => $word->getTranslations()->toArray(),
        ];
    }
}
