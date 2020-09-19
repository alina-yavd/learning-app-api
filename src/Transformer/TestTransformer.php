<?php

namespace App\Transformer;

use App\ViewModel\TestDTO;
use League\Fractal\TransformerAbstract;

final class TestTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['word', 'answers', 'group'];

    public function transform(TestDTO $test): array
    {
        return [];
    }

    public function includeWord(TestDTO $test)
    {
        $word = $test->getWord();

        return $this->item($word, new WordTransformer());
    }

    public function includeAnswers(TestDTO $test)
    {
        $answers = $test->getAnswers();

        return $this->collection($answers, new WordTranslationTransformer());
    }

    public function includeGroup(TestDTO $test)
    {
        if (null !== $test->getGroup()) {
            $group = $test->getGroup();

            return $this->item($group, new WordGroupTransformer());
        } else {
            return null;
        }
    }
}
