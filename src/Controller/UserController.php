<?php

namespace App\Controller;

use App\Service\User\UserDataServiceInterface;
use App\Transformer\UserLearningTransformer;
use App\Transformer\UserProgressTransformer;
use App\Transformer\UserTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
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

    /**
     * @Route("/progress", methods={"GET"})
     */
    public function progress(): JsonResponse
    {
        $user = $this->getUser();
        $progress = $user->getProgress();
        $data = new Collection($progress, new UserProgressTransformer());

        return new JsonResponse($this->transformer->createData($data));
    }

    /**
     * @Route("/learning", methods={"GET"})
     */
    public function viewLearning(UserDataServiceInterface $userDataService): JsonResponse
    {
        try {
            $learning = $userDataService->getLearning($this->getUser());
        } catch (\Exception $e) {
            return $this->errorExit(new JsonResponse(), $e->getMessage());
        }

        $data = new Item($learning, new UserLearningTransformer());

        return new JsonResponse($this->transformer->createData($data));
    }

    /**
     * @Route("/learning", methods={"POST"})
     */
    public function updateLearning(Request $request, UserDataServiceInterface $userDataService): JsonResponse
    {
        try {
            $userDataService->updateLearning($this->getUser(), $request);
        } catch (\Exception $e) {
            return $this->errorExit(new JsonResponse(), $e->getMessage());
        }

        return $this->successExit(new JsonResponse());
    }
}
