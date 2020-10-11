<?php

namespace App\Transformer;

use App\ViewModel\TestViewModel;
use League\Fractal\TransformerAbstract;

final class TestTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['word', 'answers', 'group'];

    public function transform(TestViewModel $test): array
    {
        return [];
    }

    public function includeWord(TestViewModel $test)
    {
        $word = $test->getWord();

        return $this->item($word, new WordTransformer());
    }

    public function includeAnswers(TestViewModel $test)
    {
        $answers = $test->getAnswers();

        return $this->collection($answers, new WordTransformer());
    }

    public function includeGroup(TestViewModel $test)
    {
        if (null !== $test->getGroup()) {
            $group = $test->getGroup();

            return $this->item($group, new WordGroupTransformer());
        } else {
            return null;
        }
    }
}
