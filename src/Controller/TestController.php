<?php

namespace App\Controller;

use App\Provider\TestProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    private TestProviderInterface $testProvider;

    public function __construct(TestProviderInterface $testProvider)
    {
        $this->testProvider = $testProvider;
    }

    /**
     * @Route("/api/test", methods={"GET"}, name="api_test")
     */
    public function index(): JsonResponse
    {
        $test = $this->testProvider->getTest();

        $json = [
            'item' => [
                'id' => $test->getWord()->getId(),
                'text' => $test->getWord()->getText(),
            ],
            'items' => $test->getAnswers()->map(function ($answer) {
                return ['id' => $answer->getId(), 'text' => $answer->getText()];
            }),
        ];

        $response = new JsonResponse();
        $response->setContent(json_encode($json));
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
