<?php

namespace App\Controller;

use App\Service\LanguageProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class LanguageController extends AbstractController
{
    private LanguageProviderInterface $languageProvider;

    public function __construct(LanguageProviderInterface $languageProvider)
    {
        $this->languageProvider = $languageProvider;
    }

    /**
     * Get languages list.
     *
     * @Route("/api/language", methods={"GET"}, name="api_language")
     */
    public function index(): JsonResponse
    {
        $items = $this->languageProvider->getList();

        $json = [
            'items' => $items->map(function ($item) {
                return [
                    'id' => $item->getId(),
                    'code' => $item->getCode(),
                    'name' => $item->getName(),
                ];
            }),
        ];

        return new JsonResponse($json);
    }
}
