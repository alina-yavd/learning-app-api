<?php

namespace App\Transformer;

use App\ViewModel\WordGroupViewModel;
use League\Fractal\TransformerAbstract;

class WordGroupTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['language', 'translation'];

    public function transform(WordGroupViewModel $group): array
    {
        return [
            'id' => $group->getId(),
            'name' => $group->getName(),
            'progress' => $group->getProgress(),
        ];
    }

    public function includeLanguage(WordGroupViewModel $group)
    {
        $language = $group->getLanguage()->getItem();

        return $this->item($language, new LanguageTransformer());
    }

    public function includeTranslation(WordGroupViewModel $group)
    {
        $translation = $group->getTranslation()->getItem();

        return $this->item($translation, new LanguageTransformer());
    }
}
