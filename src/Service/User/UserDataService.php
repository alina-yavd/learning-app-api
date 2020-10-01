<?php

namespace App\Service\User;

use App\DTO\UserDataDTO;
use App\Repository\UserRepository;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserDataService implements UserDataServiceInterface
{
    private UserRepository $repository;
    private ValidatorInterface $validator;

    public function __construct(
        UserRepository $userRepository,
        ValidatorInterface $validator
    ) {
        $this->repository = $userRepository;
        $this->validator = $validator;
    }

    public function update(UserInterface $user, Request $request): void
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $firstName = $request->request->get('firstName');
        $lastName = $request->request->get('lastName');

        $userWithEmail = $this->repository->findOneBy(['email' => $email]);

        if ($userWithEmail && $userWithEmail !== $user) {
            throw new InvalidArgumentException('This email is already registered.');
        }

        $userDataDTO = new UserDataDTO($email, $password, $firstName, $lastName);

        $errors = $this->validator->validate($userDataDTO);
        if (count($errors) > 0) {
            throw new InvalidArgumentException((string) $errors);
        }

        $this->repository->update($user, $userDataDTO);
    }
}
