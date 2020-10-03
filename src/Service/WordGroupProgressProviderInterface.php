<?php

namespace App\Service;

use App\Entity\WordGroup;
use Symfony\Component\Security\Core\User\UserInterface;

interface WordGroupProgressProviderInterface
{
    public function getProgress(UserInterface $user, WordGroup $group): float;
}
