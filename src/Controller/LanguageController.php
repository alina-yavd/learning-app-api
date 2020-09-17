<?php

namespace App\Controller;

use App\Exception\LanguageCreateException;
use App\Service\LanguageProviderInterface;
use App\Transformer\LanguageTransformer;
use InvalidArgumentException;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\Assert;

/**
 * @Route("/api/language", methods={"GET"}, name="api_language")
 */
class LanguageController extends AbstractController
{
    use JsonExit;

    private LanguageProviderInterface $languageProvider;
    private Manager $transformer;

    public function __construct(LanguageProviderInterface $languageProvider, Manager $manager)
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
            Assert::notEmpty($code);
            Assert::length($code, 2);
            Assert::notEmpty($name);
        } catch (InvalidArgumentException $e) {
            return $this->errorExit($response, sprintf('Required parameters: %s.', implode(', ', ['code', 'name'])));
        }

        try {
            $this->languageProvider->createItem($code, $name);
        } catch (LanguageCreateException $e) {
            return $this->errorExit($response, $e->getMessage());
        }

        $json = [
            'status' => 'success',
            'message' => 'Language successfully created.',
        ];

        $response->setData($json);

        return $response;
    }
}
