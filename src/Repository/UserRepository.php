<?php

namespace App\Repository;

use App\DTO\UserDataDTO;
use App\DTO\UserDTO;
use App\Entity\User;
use App\Entity\UserLearning;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(ManagerRegistry $registry, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct($registry, User::class);
        $this->passwordEncoder = $passwordEncoder;
    }

    public function create(UserDTO $userDTO): UserInterface
    {
        $user = new User($userDTO->getEmail());
        $encodedPassword = $this->passwordEncoder->encodePassword($user, $userDTO->getPassword());
        $user->setPassword($encodedPassword);
        $user->setFirstName($userDTO->getFirstName());
        $user->setLastName($userDTO->getLastName());

        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }

    public function update(UserInterface $user, UserDataDTO $userDataDTO): UserInterface
    {
        if ($userDataDTO->hasEmail()) {
            $user->setEmail($userDataDTO->getEmail());
        }
        if ($userDataDTO->hasFirstName()) {
            $user->setFirstName($userDataDTO->getFirstName());
        }
        if ($userDataDTO->hasLastName()) {
            $user->setLastName($userDataDTO->getLastName());
        }
        if ($userDataDTO->hasPassword()) {
            $encodedPassword = $this->passwordEncoder->encodePassword($user, $userDataDTO->getPassword());
            $user->setPassword($encodedPassword);
        }

        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }

    public function updateUserLanguages(UserInterface $user, ?array $languages): UserLearning
    {
        $learning = $this->getUserLearning($user);

        $learning->removeLanguages();
        if ($languages) {
            foreach ($languages as $language) {
                $learning->addLanguage($language);
            }
        }

        $this->_em->persist($learning);
        $this->_em->persist($user);
        $this->_em->flush();

        return $learning;
    }

    public function updateUserGroups(UserInterface $user, ?array $groups): UserLearning
    {
        $learning = $this->getUserLearning($user);

        $learning->removeWordGroups();
        if ($groups) {
            foreach ($groups as $group) {
                $learning->addWordGroup($group);
                $learning->addLanguage($group->getLanguage());
            }
        }

        $this->_em->persist($learning);
        $this->_em->persist($user);
        $this->_em->flush();

        return $learning;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    protected function getUserLearning(UserInterface $user): UserLearning
    {
        $learning = $user->getLearning();
        if (null === $learning) {
            $learning = new UserLearning($user);
        }

        return $learning;
    }
}
