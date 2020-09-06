<?php

namespace App\Controller;

use App\Exception\ApiException;
use App\Service\TestProviderInterface;
use App\Service\WordProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    private TestProviderInterface $testProvider;
    private WordProviderInterface $wordProvider;

    public function __construct(TestProviderInterface $testProvider, WordProviderInterface $wordProvider)
    {
        $this->testProvider = $testProvider;
        $this->wordProvider = $wordProvider;
    }

    /**
     * Get random word and possible answers group.
     *
     * @Route("/api/test", methods={"GET"}, name="api_test")
     */
    public function index(): JsonResponse
    {
        $response = new JsonResponse();
        $test = $this->testProvider->getTest();

        $json = [
            'item' => [
                'id' => $test->getWord()->getId(),
                'text' => $test->getWord()->getText(),
            ],
            'items' => $test->getAnswers()->map(function ($item) {
                return [
                    'id' => $item->getId(),
                    'text' => $item->getText(),
                ];
            }),
        ];

        $response->setData($json);
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * Check if answer is correct.
     *
     * @Route("/api/test", methods={"POST"}, name="api_test_check")
     */
    public function check(Request $request): JsonResponse
    {
        $response = new JsonResponse();
        $response->headers->set('Access-Control-Allow-Origin', '*');

        $wordId = (int) $request->request->get('wordId') ?? null;
        $answerId = (int) $request->request->get('answerId') ?? null;

        if (!$wordId || !$answerId) {
            $exception = new ApiException(406, 'Missing required parameters.');
            $response->setData($exception->getErrorDetails());

            return $response;
        }

        $word = $this->wordProvider->getItem($wordId);
        $answers = $this->testProvider->getCorrectAnswer($wordId);
        $result = $this->testProvider->checkAnswer($wordId, $answerId);

        $json = [
            'word' => $word->getInfo(),
            'answers' => $answers->map(function ($item) {
                return $item->getInfo();
            }),
            'result' => $result,
        ];

        $response->setData($json);

        return $response;
    }
}
