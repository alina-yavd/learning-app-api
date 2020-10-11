<?php

namespace App\Transformer;

use App\Entity\UserProgress;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\TransformerAbstract;

class UserProgressTransformer extends TransformerAbstract
{
    public function transform(UserProgress $progress): array
    {
        $this->currentScope->getManager()->setSerializer(new ArraySerializer());

        return [
            'word' => $progress->getWord()->getInfo(),
            'count' => $progress->getTestCount(),
            'passed' => $progress->getPassCount(),
        ];
    }
}
