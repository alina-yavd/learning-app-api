<?php

namespace App\Controller;

use App\Exception\LanguageCreateException;
use App\Service\LanguageProviderInterface;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\Assert;

class LanguageController extends AbstractController
{
    use JsonExit;

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

    /**
     * Create language.
     *
     * @Route("/api/language/create", methods={"POST"}, name="api_language_create")
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
