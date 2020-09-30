<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Exception\UserAuthException;
use App\Repository\ApiRefreshTokenRepository;
use App\Security\ApiTokenAuthenticator;
use App\Service\User\UserAuthInterface;
use App\Transformer\ApiTokenTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/auth")
 */
final class AuthController extends ApiController
{
    private Manager $transformer;
    private ValidatorInterface $validator;
    private UserAuthInterface $userAuth;

    public function __construct(Manager $manager, ValidatorInterface $validator, UserAuthInterface $userAuth)
    {
        $this->transformer = $manager;
        $this->transformer->setSerializer(new ArraySerializer());
        $this->validator = $validator;
        $this->userAuth = $userAuth;
    }

    /**
     * @Route("/token", methods={"POST"}, name="api_token")
     */
    public function token(Request $request): JsonResponse
    {
        $response = new JsonResponse();

        $userDTO = new UserDTO($request->request->get('email'), $request->request->get('password'));

        $errors = $this->validator->validate($userDTO);
        if (count($errors) > 0) {
            return $this->errorExit($response, (string) $errors);
        }

        try {
            $token = $this->userAuth->login($userDTO);
        } catch (UserAuthException $e) {
            return $this->errorExit(new JsonResponse(), $e->getMessage(), 401);
        }

        $data = new Item($token, new ApiTokenTransformer());

        return new JsonResponse($this->transformer->createData($data));
    }

    /**
     * @Route("/refresh", methods={"POST"}, name="api_refresh_token")
     */
    public function refreshToken(
        Request $request,
        ApiTokenAuthenticator $tokenAuthenticator,
        ApiRefreshTokenRepository $refreshTokenRepository
    ): JsonResponse {

        // TODO: fix "Invalid API token." error if valid refresh token passed
        $credentials = $tokenAuthenticator->getCredentials($request);
        $refreshToken = $refreshTokenRepository->findOneBy(['refreshToken' => $credentials]);

        if (!$refreshToken) {
            return $this->errorExit(new JsonResponse(), 'Invalid API refresh token.', 401);
        }

        try {
            $token = $this->userAuth->refreshToken($refreshToken);
        } catch (UserAuthException $e) {
            return $this->errorExit(new JsonResponse(), $e->getMessage(), 401);
        }

        $data = new Item($token, new ApiTokenTransformer());

        return new JsonResponse($this->transformer->createData($data));
    }

    /**
     * @Route("/register", methods={"POST"}, name="api_register")
     */
    public function register(Request $request)
    {
        $response = new JsonResponse();

        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $firstName = $request->request->get('firstName');
        $lastName = $request->request->get('lastName');

        $userDTO = new UserDTO($email, $password, $firstName, $lastName);

        $errors = $this->validator->validate($userDTO);
        if (count($errors) > 0) {
            return $this->errorExit($response, (string) $errors);
        }

        try {
            $token = $this->userAuth->register($userDTO);
        } catch (UserAuthException $e) {
            return $this->errorExit($response, $e->getMessage());
        }

        $data = new Item($token, new ApiTokenTransformer());

        return new JsonResponse($this->transformer->createData($data));
    }

    /**
     * @Route("/logout", methods={"GET"}, name="api_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
