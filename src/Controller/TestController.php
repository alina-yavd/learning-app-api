<?php

namespace App\Controller;

use App\Exception\ApiException;
use App\Provider\TestProviderInterface;
use App\Provider\WordProviderInterface;
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
     * Get random word and it's answers list.
     *
     * @Route("/api/test", methods={"GET"}, name="api_test")
     */
    public function index(): JsonResponse
    {
        $test = $this->testProvider->getTest();

        $json = [
            'word' => [
                'id' => $test->getWord()->getId(),
                'text' => $test->getWord()->getText(),
            ],
            'answers' => $test->getAnswers()->map(function ($answer) {
                return ['id' => $answer->getId(), 'text' => $answer->getText()];
            }),
        ];

        $response = new JsonResponse($json);
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * Check if answer is correct.
     *
     * @Route("/api/test/check", methods={"POST"}, name="api_test_check")
     */
    public function check(Request $request): JsonResponse
    {
        $response = new JsonResponse();
        $response->headers->set('Access-Control-Allow-Origin', '*');

        $wordId = (int) $request->request->get('wordId') ?? null;
        $answerId = (int) $request->request->get('answerId') ?? null;

        if (!$wordId || !$answerId) {
            $exception = new ApiException(406, 'Missing required parameters.');
            $response->setContent(json_encode($exception->getErrorDetails()));

            return $response;
        }

        $word = $this->wordProvider->getItem($wordId);
        $answers = $this->testProvider->getCorrectAnswer($wordId);
        $result = $this->testProvider->checkAnswer($wordId, $answerId);

        $json = [
            'word' => [
                'id' => $word->getId(),
                'text' => $word->getText(),
            ],
            'answers' => $answers->map(function ($answer) {
                return ['id' => $answer->getId(), 'text' => $answer->getText()];
            }),
            'result' => $result,
        ];

        $response->setContent(json_encode($json));

        return $response;
    }
}
