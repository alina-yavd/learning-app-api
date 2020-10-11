<?php

namespace App\Transformer;

use App\Collection\Languages;
use App\Collection\WordGroups;
use App\Entity\Language;
use App\Entity\UserLearning;
use App\Entity\WordGroup;
use League\Fractal\TransformerAbstract;

class UserLearningTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['groups', 'languages'];

    public function transform(UserLearning $learning): array
    {
        return [];
    }

    public function includeGroups(UserLearning $learning)
    {
        $groups = $learning->getWordGroups()->map(function (WordGroup $item) {
            return $item->getItem();
        });

        return $this->collection(new WordGroups(...$groups), new WordGroupTransformer());
    }

    public function includeLanguages(UserLearning $learning)
    {
        $languages = $learning->getLanguages()->map(function (Language $item) {
            return $item->getItem();
        });

        return $this->collection(new Languages(...$languages), new LanguageTransformer());
    }
}
