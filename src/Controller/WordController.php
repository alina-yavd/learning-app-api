<?php

namespace App\Controller;

use App\Service\WordProviderInterface;
use App\Transformer\WordTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\ArraySerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/word")
 */
class WordController extends ApiController
{
    private WordProviderInterface $wordProvider;
    private Manager $transformer;

    public function __construct(WordProviderInterface $wordProvider, Manager $manager)
    {
        $this->wordProvider = $wordProvider;
        $this->transformer = $manager;
        $this->transformer->setSerializer(new ArraySerializer());
    }

    /**
     * Get words list.
     *
     * @Route(methods={"GET"})
     */
    public function view(): JsonResponse
    {
        $words = $this->wordProvider->getList();
        $data = new Collection($words, new WordTransformer());

        return new JsonResponse($this->transformer->createData($data));
    }
}
