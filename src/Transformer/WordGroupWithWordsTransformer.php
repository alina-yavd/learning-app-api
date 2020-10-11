<?php

namespace App\Transformer;

use App\Entity\Word;
use App\ViewModel\WordGroupViewModel;

final class WordGroupWithWordsTransformer extends WordGroupTransformer
{
    public function transform(WordGroupViewModel $group): array
    {
        $words = $group->getWords()->filter(fn (Word $item) => $item->getLanguage() === $group->getLanguage());

        return [
            'id' => $group->getId(),
            'name' => $group->getName(),
            'words' => $words->map(fn (Word $item) => $item->getInfoWithTranslation($group->getTranslation()))->getValues(),
            'progress' => $group->getProgress(),
        ];
    }
}
