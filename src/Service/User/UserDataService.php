<?php

namespace App\Service\User;

use App\DTO\UserDataDTO;
use App\Entity\UserLearning;
use App\Repository\LanguageRepository;
use App\Repository\UserRepository;
use App\Repository\WordGroupRepository;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserDataService implements UserDataServiceInterface
{
    private UserRepository $repository;
    private WordGroupRepository $groupRepository;
    private LanguageRepository $languageRepository;
    private ValidatorInterface $validator;

    public function __construct(
        UserRepository $userRepository,
        WordGroupRepository $groupRepository,
        LanguageRepository $languageRepository,
        ValidatorInterface $validator
    ) {
        $this->repository = $userRepository;
        $this->groupRepository = $groupRepository;
        $this->languageRepository = $languageRepository;
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

    public function getLearning(UserInterface $user): UserLearning
    {
        $learning = $user->getLearning();
        if (null === $learning) {
            $learning = new UserLearning($user);
        }

        return $learning;
    }

    // maybe move logic to checkAnswer event
    public function updateLearning(UserInterface $user, Request $request): void
    {
        $languageIds = $request->request->get('languageIds');
        if (null !== $languageIds) {
            $this->updateUserLanguages($user, $languageIds);
        }

        $groupIds = $request->request->get('groupIds');
        if (null !== $groupIds) {
            $this->updateUserGroups($user, $groupIds);
        }
    }

    protected function updateUserLanguages($user, $languageIds): void
    {
        if (empty($languageIds)) {
            $languages = null;
        } else {
            $ids = explode(',', $languageIds);
            $languages = \array_map(fn (int $languageId) => $this->languageRepository->getById($languageId), $ids);
            $languages = \array_filter($languages);
        }

        $this->repository->updateUserLanguages($user, $languages);
    }

    protected function updateUserGroups($user, $groupIds): void
    {
        if (empty($groupIds)) {
            $groups = null;
        } else {
            $ids = explode(',', $groupIds);
            $groups = \array_map(fn (int $groupId) => $this->groupRepository->getById($groupId), $ids);
            $groups = \array_filter($groups);
        }

        $this->repository->updateUserGroups($user, $groups);
    }
}
