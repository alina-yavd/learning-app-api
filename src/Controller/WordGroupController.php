<?php

namespace App\Controller;

use App\Exception\EntityNotFoundException;
use App\Service\WordGroupProviderInterface;
use App\Transformer\WordGroupTransformer;
use App\Transformer\WordGroupWithWordsTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/group")
 */
class WordGroupController extends AbstractController
{
    use JsonExit;

    private WordGroupProviderInterface $groupProvider;
    private Manager $transformer;

    public function __construct(WordGroupProviderInterface $groupProvider, Manager $manager)
    {
        $this->groupProvider = $groupProvider;
        $this->transformer = $manager;
    }

    /**
     * Get word groups list.
     *
     * @Route(methods={"GET"})
     */
    public function list(Request $request): JsonResponse
    {
        $languageCode = $request->query->get('language');
        $translationCode = $request->query->get('translation');

        $items = $this->groupProvider->getList(['language' => $languageCode, 'translation' => $translationCode]);
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

        return new JsonResponse($this->transformer->createData($data));
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
            $this->groupProvider->removeItem((int) $id, $deleteWithData);
        } catch (EntityNotFoundException $e) {
            return $this->errorExit($response, $e->getMessage(), 404);
        }

        $json = [
            'status' => 'success',
            'message' => 'Word list successfully deleted.',
        ];

        $response->setData($json);

        return $response;
    }
}
