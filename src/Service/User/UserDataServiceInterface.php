<?php

namespace App\Service\User;

use App\Entity\UserLearning;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

interface UserDataServiceInterface
{
    public function update(UserInterface $user, Request $request): void;

    public function getLearning(UserInterface $user): UserLearning;

    public function updateLearning(UserInterface $user, Request $request): void;
}
