<?php

namespace App\Controller;

use App\Exception\ApiException;
use App\Exception\EntityNotFoundException;
use App\Service\TestProviderInterface;
use App\Service\WordGroupProviderInterface;
use App\Service\WordProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    private TestProviderInterface $testProvider;
    private WordProviderInterface $wordProvider;
    private WordGroupProviderInterface $groupProvider;

    public function __construct(TestProviderInterface $testProvider, WordProviderInterface $wordProvider, WordGroupProviderInterface $groupProvider)
    {
        $this->testProvider = $testProvider;
        $this->wordProvider = $wordProvider;
        $this->groupProvider = $groupProvider;
    }

    /**
     * Get random word and possible answers group.
     *
     * @Route("/api/test", methods={"GET"}, name="api_test")
     */
    public function index(Request $request): JsonResponse
    {
        $response = new JsonResponse();
        $response->headers->set('Access-Control-Allow-Origin', '*');

        // TODO: catch 'Entity "Word" with ID 2 not found.' exception
        $groupId = $request->query->getInt('groupId');
        if ($groupId) {
            $group = $this->testProvider->setGroup($groupId);
        } else {
            $group = null;
        }

        $test = $this->testProvider->getTest();

        $json = [
            'word' => [
                'id' => $test->getWord()->getId(),
                'text' => $test->getWord()->getText(),
            ],
            'answers' => $test->getAnswers()->map(function ($item) {
                return [
                    'id' => $item->getId(),
                    'text' => $item->getText(),
                ];
            }),
        ];

        if (null !== $group) {
            $json['group'] = [
                'id' => $group->getId(),
                'name' => $group->getName(),
            ];
        }

        $response->setData($json);

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

        $wordId = $request->request->getInt('wordId');
        $answerId = $request->request->getInt('answerId');

        if (!$wordId || !$answerId) {
            $exception = new ApiException(406, 'Missing required parameters.');
            $response->setData($exception->getErrorDetails());

            return $response;
        }

        try {
            $word = $this->wordProvider->getItem($wordId);
        } catch (EntityNotFoundException $e) {
            $exception = new ApiException(404, $e->getMessage());
            $response->setData($exception->getErrorDetails());

            return $response;
        }

        $result = $this->testProvider->checkAnswer($wordId, $answerId);

        $json = [
            'word' => [
                'id' => $word->getId(),
                'text' => $word->getText(),
                'translations' => $word->getTranslations()->toArray(),
            ],
            'result' => $result,
        ];

        $response->setData($json);

        return $response;
    }
}
