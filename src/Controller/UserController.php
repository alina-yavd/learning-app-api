<?php

namespace App\Controller;

use App\Transformer\UserTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/user")
 * @IsGranted("ROLE_USER")
 */
final class UserController extends ApiController
{
    private Manager $transformer;

    public function __construct(Manager $manager)
    {
        $this->transformer = $manager;
    }

    /**
     * @Route(methods={"GET"})
     */
    public function view(): JsonResponse
    {
        $user = $this->getUser();
        $data = new Item($user, new UserTransformer());

        return new JsonResponse($this->transformer->createData($data));
    }

    /**
     * @Route(methods={"POST"})
     */
    public function update(): JsonResponse
    {
        // TODO: implement user profile editing
        return $this->successExit(new JsonResponse());
    }
}
