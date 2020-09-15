<?php

namespace App\Controller;

use App\Exception\EntityNotFoundException;
use App\Service\TestProviderInterface;
use App\Service\WordGroupProviderInterface;
use App\Service\WordProviderInterface;
use App\ViewModel\WordTranslationDTO;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\Assert;

class TestController extends AbstractController
{
    use JsonExit;

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

        $groupId = $request->query->getInt('groupId');

        if ($groupId) {
            try {
                $group = $this->groupProvider->getItem($groupId);
            } catch (EntityNotFoundException $e) {
                return $this->errorExit($response, sprintf('Group %s not found.', $groupId));
            }
        } else {
            $group = null;
        }

        $test = $this->testProvider->getTest($group);

        $json = [
            'word' => [
                'id' => $test->getWord()->getId(),
                'text' => $test->getWord()->getText(),
            ],
            'answers' => $test->getAnswers()->map(function (WordTranslationDTO $item) {
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

        $wordId = $request->request->getInt('wordId');
        $answerId = $request->request->getInt('answerId');

        try {
            Assert::notEmpty($wordId);
            Assert::notEmpty($answerId);
        } catch (InvalidArgumentException $e) {
            return $this->errorExit($response, sprintf('Required parameters: %s.', implode(', ', ['wordId', 'answerId'])));
        }

        try {
            $word = $this->wordProvider->getItem($wordId);
        } catch (EntityNotFoundException $e) {
            return $this->errorExit($response, $e->getMessage());
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
