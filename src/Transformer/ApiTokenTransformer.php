<?php

namespace App\Transformer;

use App\DTO\ApiTokenDTO;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\TransformerAbstract;

final class ApiTokenTransformer extends TransformerAbstract
{
    public function transform(ApiTokenDTO $apiTokenDTO): array
    {
        $this->currentScope->getManager()->setSerializer(new ArraySerializer());

        return [
            'token' => $apiTokenDTO->getToken(),
            'token_type' => 'Bearer',
            'expires_in' => 3600,
            'refresh_token' => $apiTokenDTO->getRefreshToken(),
        ];
    }
}
