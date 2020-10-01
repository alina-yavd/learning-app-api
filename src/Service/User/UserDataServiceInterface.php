<?php

namespace App\Service\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

interface UserDataServiceInterface
{
    public function update(UserInterface $user, Request $request): void;
}
