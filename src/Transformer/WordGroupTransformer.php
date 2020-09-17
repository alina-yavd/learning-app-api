<?php

namespace App\Transformer;

use App\ViewModel\WordGroupDTO;
use League\Fractal\TransformerAbstract;

class WordGroupTransformer extends TransformerAbstract
{
    public function transform(WordGroupDTO $group): array
    {
        return [
            'id' => $group->getId(),
            'name' => $group->getName(),
            'language' => $group->getLanguage()->getInfo(),
            'translation' => $group->getTranslation()->getInfo(),
        ];
    }
}
