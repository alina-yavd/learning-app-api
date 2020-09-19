<?php

namespace App\Transformer;

use App\ViewModel\WordGroupDTO;

final class WordGroupWithWordsTransformer extends WordGroupTransformer
{
    public function transform(WordGroupDTO $group): array
    {
        return [
            'id' => $group->getId(),
            'name' => $group->getName(),
            'words' => $group->getWords()->toArray(),
        ];
    }
}
