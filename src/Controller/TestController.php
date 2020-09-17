<?php

namespace App\Controller;

use App\Exception\EntityNotFoundException;
use App\Service\TestProviderInterface;
use App\Service\WordGroupProviderInterface;
use App\Service\WordProviderInterface;
use App\Transformer\TestTransformer;
use App\Transformer\WordTransformer;
use InvalidArgumentException;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\Assert;

/**
 * @Route("/api/test")
 */
class TestController extends AbstractController
{
    use JsonExit;

    private TestProviderInterface $testProvider;
    private WordProviderInterface $wordProvider;
    private WordGroupProviderInterface $groupProvider;
    private Manager $transformer;

    public function __construct(
        TestProviderInterface $testProvider,
        WordProviderInterface $wordProvider,
        WordGroupProviderInterface $groupProvider,
        Manager $manager
    ) {
        $this->testProvider = $testProvider;
        $this->wordProvider = $wordProvider;
        $this->groupProvider = $groupProvider;
        $this->transformer = $manager;
        $this->transformer->setSerializer(new ArraySerializer());
    }

    /**
     * Get random word and possible answers group.
     *
     * @Route(methods={"GET"})
     */
    public function view(Request $request): JsonResponse
    {
        $response = new JsonResponse();

        $groupId = $request->query->getInt('groupId');

        try {
            $test = $this->testProvider->getTest($groupId);
        } catch (EntityNotFoundException $e) {
            return $this->errorExit($response, $e->getMessage());
        }

        $data = new Item($test, new TestTransformer());

        return new JsonResponse($this->transformer->createData($data));
    }

    /**
     * Check if answer is correct.
     *
     * @Route(methods={"POST"})
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
        $wordData = new Item($word, new WordTransformer());
        $json = [
            'word' => $this->transformer->createData($wordData),
            'result' => $result,
        ];

        $response->setData($json);

        return $response;
    }
}
