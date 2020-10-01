<?php

namespace App\Controller;

use App\Service\User\UserDataServiceInterface;
use App\Transformer\UserTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
        $this->transformer->setSerializer(new ArraySerializer());
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
    public function update(Request $request, UserDataServiceInterface $userDataService): JsonResponse
    {
        try {
            $userDataService->update($this->getUser(), $request);
        } catch (\Exception $e) {
            return $this->errorExit(new JsonResponse(), $e->getMessage());
        }

        return $this->successExit(new JsonResponse());
    }
}
