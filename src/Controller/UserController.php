<?php

namespace App\Controller;

use App\Service\User\UserDataServiceInterface;
use App\Transformer\UserProgressTransformer;
use App\Transformer\UserTransformer;
use App\Transformer\WordGroupTransformer;
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
    private UserDataServiceInterface $userDataService;

    public function __construct(Manager $manager, UserDataServiceInterface $userDataService)
    {
        $this->transformer = $manager;
        $this->transformer->setSerializer(new ArraySerializer());
        $this->userDataService = $userDataService;
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
    public function update(Request $request): JsonResponse
    {
        try {
            $this->userDataService->update($request);
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
     * @Route("/group", methods={"GET"})
     */
    public function viewGroups(): JsonResponse
    {
        try {
            $items = $this->userDataService->getLearningGroups();
        } catch (\Exception $e) {
            return $this->errorExit(new JsonResponse(), $e->getMessage());
        }

        if (!$items) {
            return new JsonResponse();
        }

        $data = new Collection($items, new WordGroupTransformer());

        return new JsonResponse($this->transformer->createData($data));
    }

    /**
     * @Route("/group", methods={"POST"})
     */
    public function updateGroups(Request $request): JsonResponse
    {
        try {
            $this->userDataService->updateLearningGroups($request);
        } catch (\Exception $e) {
            return $this->errorExit(new JsonResponse(), $e->getMessage());
        }

        return $this->successExit(new JsonResponse());
    }

    /**
     * @Route("/group/{id}", methods={"POST"})
     */
    public function addGroup(int $id): JsonResponse
    {
        try {
            $this->userDataService->addLearningGroup($id);
        } catch (\Exception $e) {
            return $this->errorExit(new JsonResponse(), $e->getMessage());
        }

        return $this->successExit(new JsonResponse());
    }

    /**
     * @Route("/group/{id}", methods={"DELETE"})
     */
    public function removeGroup(int $id): JsonResponse
    {
        try {
            $this->userDataService->removeLearningGroup($id);
        } catch (\Exception $e) {
            return $this->errorExit(new JsonResponse(), $e->getMessage());
        }

        return $this->successExit(new JsonResponse());
    }
}
