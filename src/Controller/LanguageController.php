<?php

namespace App\Controller;

use App\Exception\LanguageAlreadyExistsException;
use App\Service\LanguageServiceInterface;
use App\Transformer\LanguageTransformer;
use InvalidArgumentException;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/language", methods={"GET"}, name="api_language")
 */
class LanguageController extends ApiController
{
    private LanguageServiceInterface $languageProvider;
    private Manager $transformer;

    public function __construct(LanguageServiceInterface $languageProvider, Manager $manager)
    {
        $this->languageProvider = $languageProvider;
        $this->transformer = $manager;
    }

    /**
     * Get languages list.
     *
     * @Route(methods={"GET"})
     */
    public function view(): JsonResponse
    {
        $items = $this->languageProvider->getList();
        $data = new Collection($items, new LanguageTransformer());

        return new JsonResponse($this->transformer->createData($data));
    }

    /**
     * Create language.
     *
     * @Route("/create", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $response = new JsonResponse();
        $code = $request->request->get('code');
        $name = $request->request->get('name');

        try {
            $this->languageProvider->createItem($code, $name);
        } catch (LanguageAlreadyExistsException | InvalidArgumentException $e) {
            return $this->errorExit($response, $e->getMessage());
        }

        return $this->successExit($response);
    }
}
