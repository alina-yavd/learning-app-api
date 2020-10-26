<?php

namespace App\Transformer;

use App\Entity\User;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\TransformerAbstract;

final class UserTransformer extends TransformerAbstract
{
    public function transform(User $user): array
    {
        $this->currentScope->getManager()->setSerializer(new ArraySerializer());

        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
        ];
    }
}
