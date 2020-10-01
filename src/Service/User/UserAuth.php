<?php

namespace App\Service\User;

use App\DTO\ApiTokenDTO;
use App\DTO\UserDTO;
use App\Entity\ApiRefreshToken;
use App\Entity\ApiToken;
use App\Exception\UserAuthException;
use App\Repository\ApiRefreshTokenRepository;
use App\Repository\UserRepository;
use App\Security\ApiTokenAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserAuth implements UserAuthInterface
{
    private EntityManagerInterface $em;
    private ValidatorInterface $validator;
    private UserRepository $repository;
    private UserPasswordEncoderInterface $passwordEncoder;
    private ApiTokenAuthenticator $tokenAuthenticator;
    private ApiRefreshTokenRepository $refreshTokenRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        UserRepository $repository,
        UserPasswordEncoderInterface $passwordEncoder,
        ApiTokenAuthenticator $tokenAuthenticator,
        ApiRefreshTokenRepository $refreshTokenRepository
    ) {
        $this->em = $entityManager;
        $this->validator = $validator;
        $this->repository = $repository;
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenAuthenticator = $tokenAuthenticator;
        $this->refreshTokenRepository = $refreshTokenRepository;
    }

    public function login(Request $request): ApiTokenDTO
    {
        $userDTO = new UserDTO($request->request->get('email'), $request->request->get('password'));

        $errors = $this->validator->validate($userDTO);
        if (count($errors) > 0) {
            throw new InvalidArgumentException((string) $errors);
        }

        $user = $this->repository->findOneBy(['email' => $userDTO->getEmail()]);

        if (!$user || !$this->passwordEncoder->isPasswordValid($user, $userDTO->getPassword())) {
            throw new UserAuthException('User credentials are not correct.');
        }

        return $this->generateToken($user);
    }

    public function refreshToken(Request $request): ApiTokenDTO
    {
        // TODO: fix "Invalid API token." error if valid refresh token passed
        $credentials = $this->tokenAuthenticator->getCredentials($request);
        $refreshToken = $this->refreshTokenRepository->findOneBy(['refreshToken' => $credentials]);

        if (!$refreshToken) {
            throw new UserAuthException('Invalid API refresh token.');
        }

        if ($refreshToken->isExpired()) {
            throw new UserAuthException('API refresh token expired.');
        }

        $token = $refreshToken->getToken();

        return $this->generateToken($token->getUser());
    }

    public function register(Request $request): ApiTokenDTO
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $firstName = $request->request->get('firstName');
        $lastName = $request->request->get('lastName');

        $userDTO = new UserDTO($email, $password, $firstName, $lastName);

        $errors = $this->validator->validate($userDTO);
        if (count($errors) > 0) {
            throw new InvalidArgumentException((string) $errors);
        }

        $user = $this->repository->findOneBy(['email' => $userDTO->getEmail()]);

        if ($user) {
            throw new UserAuthException('User with this email already exists.');
        }

        $user = $this->repository->create($userDTO);

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
