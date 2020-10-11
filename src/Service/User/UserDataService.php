<?php

namespace App\Service\User;

use App\Collection\WordGroups;
use App\DTO\UserDataDTO;
use App\Entity\WordGroup;
use App\Repository\LanguageRepository;
use App\Repository\UserRepository;
use App\Repository\WordGroupRepository;
use App\Service\WordGroupFilter;
use App\Service\WordGroupProviderInterface;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserDataService implements UserDataServiceInterface
{
    private UserRepository $repository;
    private WordGroupRepository $groupRepository;
    private WordGroupProviderInterface $groupProvider;
    private WordGroupFilter $groupFilter;
    private LanguageRepository $languageRepository;
    private ValidatorInterface $validator;
    private Security $security;

    public function __construct(
        UserRepository $userRepository,
        WordGroupRepository $groupRepository,
        WordGroupProviderInterface $groupProvider,
        WordGroupFilter $groupFiler,
        LanguageRepository $languageRepository,
        ValidatorInterface $validator,
        Security $security
    ) {
        $this->repository = $userRepository;
        $this->groupRepository = $groupRepository;
        $this->groupProvider = $groupProvider;
        $this->groupFilter = $groupFiler;
        $this->languageRepository = $languageRepository;
        $this->validator = $validator;
        $this->security = $security;
    }

    public function update(Request $request): void
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $firstName = $request->request->get('firstName');
        $lastName = $request->request->get('lastName');

        $userWithEmail = $this->repository->findOneBy(['email' => $email]);

        $user = $this->security->getUser();
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

    public function getLearningGroups(): ?WordGroups
    {
        $user = $this->security->getUser();
        $ids = $this->getGroups($user);
        if (empty($ids)) {
            return null;
        }
        $this->groupFilter->setIds($ids);

        return $this->groupProvider->getList($this->groupFilter);
    }

    public function updateLearningGroups(Request $request): void
    {
        $groupIds = $request->request->get('groupIds');
        if (null !== $groupIds) {
            $ids = empty($groupIds) ? null : \explode(',', $groupIds);
            $this->updateGroups($this->security->getUser(), $ids);
        }
    }

    public function addLearningGroup($id): void
    {
        $user = $this->security->getUser();
        $ids = $this->getGroups($user);
        if (null === $ids) {
            $ids = [$id];
        } else {
            \array_push($ids, $id);
        }
        $this->updateGroups($this->security->getUser(), \array_unique($ids));
    }

    public function removeLearningGroup($id): void
    {
        $user = $this->security->getUser();
        $ids = $this->getGroups($user);
        if (null === $ids || !\in_array($id, $ids)) {
            return;
        }
        if (false !== ($key = array_search($id, $ids))) {
            unset($ids[$key]);
        }
        $this->updateGroups($this->security->getUser(), \array_unique($ids));
    }

    protected function getGroups(UserInterface $user): ?array
    {
        $learning = $user->getLearning();
        if (null === $learning) {
            return null;
        }

        return $learning->getWordGroups()->map(fn (WordGroup $group) => $group->getId())->toArray();
    }

    protected function updateGroups(UserInterface $user, ?array $ids): void
    {
        $groups = \array_map(fn (int $groupId) => $this->groupRepository->getById($groupId), $ids);
        $groups = \array_filter($groups);

        $this->repository->updateUserGroups($user, $groups);
    }
}
