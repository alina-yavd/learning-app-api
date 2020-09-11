<?php

namespace App\Controller;

use App\Exception\EntityNotFoundException;
use App\Service\WordGroupProviderInterface;
use App\Service\WordProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WordController extends AbstractController
{
    use JsonExit;

    private WordProviderInterface $wordProvider;
    private WordGroupProviderInterface $groupProvider;

    public function __construct(WordProviderInterface $wordProvider, WordGroupProviderInterface $groupProvider)
    {
        $this->wordProvider = $wordProvider;
        $this->groupProvider = $groupProvider;
    }

    /**
     * Get words list.
     *
     * @Route("/api/word", methods={"GET"}, name="api_word")
     */
    public function index(): JsonResponse
    {
        $words = $this->wordProvider->getList();

        $json = [
            'items' => $words->map(function ($item) {
                return [
                    'id' => $item->getId(),
                    'text' => $item->getText(),
                    'translations' => $item->getTranslations()->toArray(),
                ];
            }),
        ];

        return new JsonResponse($json);
    }

    /**
     * Get word groups list and it's words.
     *
     * @Route("/api/word/group", methods={"GET"}, name="api_word_group")
     */
    public function group(): JsonResponse
    {
        $groups = $this->groupProvider->getList();

        $json = [
            'items' => $groups->map(function ($item) {
                return [
                    'id' => $item->getId(),
                    'name' => $item->getName(),
                    'words' => $item->getWords()->toArray(),
                ];
            }),
        ];

        return new JsonResponse($json);
    }

    /**
     * Delete word group.
     *
     * @Route("/api/word/group/{id}", methods={"DELETE"}, name="api_word_group_delete")
     */
    public function groupDelete(int $id, Request $request): JsonResponse
    {
        $response = new JsonResponse();
        $deleteWithData = $request->query->getBoolean('removeData');

        try {
            $this->groupProvider->removeItem((int) $id, $deleteWithData);
        } catch (EntityNotFoundException $e) {
            return $this->errorExit($response, $e->getMessage());
        }

        $json = [
            'status' => 'success',
            'message' => 'Word list successfully deleted.',
        ];

        $response->setData($json);

        return $response;
    }
}
