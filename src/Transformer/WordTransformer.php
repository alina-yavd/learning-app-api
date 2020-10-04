<?php

namespace App\Transformer;

use App\Entity\WordTranslation;
use App\ViewModel\WordViewModel;
use League\Fractal\TransformerAbstract;

final class WordTransformer extends TransformerAbstract
{
    public function transform(WordViewModel $word): array
    {
        $translations = $word->getTranslations()->map(fn (WordTranslation $item) => $item->getInfo());

        return [
            'id' => $word->getId(),
            'text' => $word->getText(),
            'language' => $word->getLanguage()->getCode(),
            'translations' => $translations->toArray(),
        ];
    }
}
