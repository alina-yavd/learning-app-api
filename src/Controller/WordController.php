<?php

namespace App\Controller;

use App\Provider\WordGroupProviderInterface;
use App\Provider\WordProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class WordController extends AbstractController
{
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
                return $item->getAnswersInfo();
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
                return array_merge(
                    $item->getInfo(),
                    ['words' => $item->getWords()->map(function ($item) {
                        return $item->getAnswersInfo();
                    })]
                );
            }),
        ];

        $response = new JsonResponse($json);
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
