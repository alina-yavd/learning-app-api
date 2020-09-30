<?php

namespace App\Service\User;

use App\DTO\ApiTokenDTO;
use App\DTO\UserDTO;
use App\Entity\ApiRefreshToken;
use App\Entity\ApiToken;
use App\Entity\User;
use App\Exception\UserAuthException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class UserAuth implements UserAuthInterface
{
    private EntityManagerInterface $em;
    private UserRepository $userRepository;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->em = $entityManager;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function login(UserDTO $userDTO): ApiTokenDTO
    {
        $user = $this->userRepository->findOneBy(['email' => $userDTO->getEmail()]);

        if (!$user || !$this->passwordEncoder->isPasswordValid($user, $userDTO->getPassword())) {
            throw new UserAuthException('User credentials are not correct.');
        }

        return $this->generateToken($user);
    }

    public function refreshToken(ApiRefreshToken $refreshToken): ApiTokenDTO
    {
        if ($refreshToken->isExpired()) {
            throw new UserAuthException('API refresh token expired.');
        }

        $token = $refreshToken->getToken();

        return $this->generateToken($token->getUser());
    }

    public function register(UserDTO $userDTO): ApiTokenDTO
    {
        $user = $this->userRepository->findOneBy(['email' => $userDTO->getEmail()]);

        if ($user) {
            throw new UserAuthException('User with this email already exists.');
        }

        $user = new User($userDTO->getEmail());
        $password = $this->passwordEncoder->encodePassword($user, $userDTO->getPassword());

        $user->setPassword($password);
        $user->setFirstName($userDTO->getFirstName());
        $user->setLastName($userDTO->getLastName());

        $this->em->persist($user);
        $this->em->flush();

        return $this->generateToken($user);
    }

    protected function generateToken($user)
    {
        $token = new ApiToken($user);
        $refreshToken = new ApiRefreshToken($token);
        $this->em->persist($token);
        $this->em->persist($refreshToken);
        $this->em->flush();

        return new ApiTokenDTO($token->getToken(), $refreshToken->getRefreshToken());
    }
}
