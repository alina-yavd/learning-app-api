<?php

namespace App\Transformer;

use App\ViewModel\WordGroupDTO;
use League\Fractal\TransformerAbstract;

class WordGroupTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['language', 'translation'];

    public function transform(WordGroupDTO $group): array
    {
        return [
            'id' => $group->getId(),
            'name' => $group->getName(),
        ];
    }

    public function includeLanguage(WordGroupDTO $group)
    {
        $language = $group->getLanguage()->getItem();

        return $this->item($language, new LanguageTransformer());
    }

    public function includeTranslation(WordGroupDTO $group)
    {
        $translation = $group->getTranslation()->getItem();

        return $this->item($translation, new LanguageTransformer());
    }
}
