<?php

namespace App\Controller;

use App\Service\WordGroupsProviderInterface;
use App\Service\WordProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class WordController extends AbstractController
{
    private WordProviderInterface $wordProvider;
    private WordGroupsProviderInterface $groupProvider;

    public function __construct(WordProviderInterface $wordProvider, WordGroupsProviderInterface $groupProvider)
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

        $response = new JsonResponse($json);
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
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

        $response = new JsonResponse($json);
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
