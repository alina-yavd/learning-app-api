<?php

namespace App\Controller;

use App\Service\WordProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class WordController extends AbstractController
{
    use JsonExit;

    private WordProviderInterface $wordProvider;

    public function __construct(WordProviderInterface $wordProvider)
    {
        $this->wordProvider = $wordProvider;
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
}
