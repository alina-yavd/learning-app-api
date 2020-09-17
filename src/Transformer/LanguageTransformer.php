<?php

namespace App\Transformer;

use App\ViewModel\LanguageDTO;
use League\Fractal\TransformerAbstract;

final class LanguageTransformer extends TransformerAbstract
{
    public function transform(LanguageDTO $language): array
    {
        return [
            'id' => $language->getId(),
            'code' => $language->getCode(),
            'name' => $language->getName(),
        ];
    }
}
