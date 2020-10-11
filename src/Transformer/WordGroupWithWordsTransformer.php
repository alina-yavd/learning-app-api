<?php

namespace App\Transformer;

use App\ViewModel\WordGroupViewModel;

final class WordGroupWithWordsTransformer extends WordGroupTransformer
{
    public function transform(WordGroupViewModel $group): array
    {
        return [
            'id' => $group->getId(),
            'name' => $group->getName(),
            'words' => $group->getWords()->toArray(),
            'progress' => $group->getProgress(),
        ];
    }
}
