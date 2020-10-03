<?php

namespace App\Controller;

use App\Exception\EntityNotFoundException;
use App\Service\WordGroupFilter;
use App\Service\WordGroupProviderInterface;
use App\Transformer\WordGroupTransformer;
use App\Transformer\WordGroupWithWordsTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/group")
 */
class WordGroupController extends ApiController
{
    private WordGroupProviderInterface $groupProvider;
    private Manager $transformer;
    private WordGroupFilter $filter;

    public function __construct(WordGroupProviderInterface $groupProvider, Manager $manager, WordGroupFilter $filter)
    {
        $this->groupProvider = $groupProvider;
        $this->transformer = $manager;
        $this->filter = $filter;
    }

    /**
     * Get word groups list.
     *
     * @Route(methods={"GET"})
     */
    public function list(Request $request): JsonResponse
    {
        $this->filter->setLanguage($request->query->get('language'));
        $this->filter->setTranslation($request->query->get('translation'));
        $items = $this->groupProvider->getList($this->filter);
        $data = new Collection($items, new WordGroupTransformer());

        return new JsonResponse($this->transformer->createData($data));
    }

    /**
     * Get word group by id.
     *
     * @Route("/{id}", methods={"GET"})
     */
    public function view(int $id): JsonResponse
    {
        $item = $this->groupProvider->getItem($id);
        $data = new Item($item, new WordGroupWithWordsTransformer());

        return new JsonResponse($this->transformer->createData($data)->toArray());
    }

    /**
     * Delete word group by id.
     *
     * @Route("/{id}", methods={"DELETE"})
     */
    public function delete(int $id, Request $request): JsonResponse
    {
        $response = new JsonResponse();
        $deleteWithData = $request->query->getBoolean('removeData');

        try {
            if ($deleteWithData) {
                $this->groupProvider->removeItemWithWords($id);
            } else {
                $this->groupProvider->removeItem($id);
            }
        } catch (EntityNotFoundException $e) {
            return $this->errorExit($response, $e->getMessage(), 404);
        }

        return $this->successExit($response);
    }
}
