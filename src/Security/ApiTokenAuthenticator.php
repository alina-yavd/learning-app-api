<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\ApiTokenRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class ApiTokenAuthenticator extends AbstractGuardAuthenticator
{
    use TargetPathTrait;

    private UserRepository $userRepository;
    private ApiTokenRepository $apiTokenRepository;
    private RouterInterface $router;
    private CsrfTokenManagerInterface $csrfTokenManager;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserRepository $userRepository, ApiTokenRepository $apiTokenRepository, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->apiTokenRepository = $apiTokenRepository;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request): bool
    {
        return $request->headers->has('Authorization')
            && 0 === strpos($request->headers->get('Authorization'), 'Bearer ');
    }

    public function getCredentials(Request $request): ?string
    {
        $authorizationHeader = $request->headers->get('Authorization');

        return substr($authorizationHeader, 7);
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?User
    {
        $token = $this->apiTokenRepository->findOneBy([
            'token' => $credentials,
        ]);

        if (!$token) {
            throw new CustomUserMessageAuthenticationException('Invalid API token.');
        }

        if ($token->isExpired()) {
            throw new CustomUserMessageAuthenticationException('API token expired.');
        }

        return $token->getUser();
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): void
    {
        // do nothing, authentication will continue
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return new JsonResponse([
            'status' => 'error',
            'message' => $exception->getMessageKey(),
        ], 401);
    }

    /**
     * Called when authentication is needed, but no token is sent.
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        $data = [
            'status' => 'error',
            'message' => 'Authentication Required',
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }

    protected function getLoginUrl(): string
    {
        return $this->router->generate('api_token');
    }
}
