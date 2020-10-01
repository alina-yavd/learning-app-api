<?php

namespace App\Controller;

use App\Exception\UserAuthException;
use App\Service\User\UserAuthInterface;
use App\Transformer\ApiTokenTransformer;
use InvalidArgumentException;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/auth")
 */
final class AuthController extends ApiController
{
    private Manager $transformer;
    private UserAuthInterface $userAuth;

    public function __construct(Manager $manager, UserAuthInterface $userAuth)
    {
        $this->transformer = $manager;
        $this->transformer->setSerializer(new ArraySerializer());
        $this->userAuth = $userAuth;
    }

    /**
     * @Route("/token", methods={"POST"}, name="api_token")
     */
    public function token(Request $request): JsonResponse
    {
        try {
            $token = $this->userAuth->login($request);
        } catch (UserAuthException | InvalidArgumentException $e) {
            return $this->errorExit(new JsonResponse(), $e->getMessage());
        }

        $data = new Item($token, new ApiTokenTransformer());

        return new JsonResponse($this->transformer->createData($data));
    }

    /**
     * @Route("/refresh", methods={"POST"}, name="api_refresh_token")
     */
    public function refreshToken(Request $request): JsonResponse
    {
        try {
            $token = $this->userAuth->refreshToken($request);
        } catch (UserAuthException | InvalidArgumentException $e) {
            return $this->errorExit(new JsonResponse(), $e->getMessage());
        }

        $data = new Item($token, new ApiTokenTransformer());

        return new JsonResponse($this->transformer->createData($data));
    }

    /**
     * @Route("/register", methods={"POST"}, name="api_register")
     */
    public function register(Request $request)
    {
        try {
            $token = $this->userAuth->register($request);
        } catch (UserAuthException | InvalidArgumentException $e) {
            return $this->errorExit(new JsonResponse(), $e->getMessage());
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
