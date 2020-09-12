<?php

namespace App\Controller;

use App\Exception\EntityNotFoundException;
use App\Service\WordGroupProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WordGroupController extends AbstractController
{
    use JsonExit;

    private WordGroupProviderInterface $groupProvider;

    public function __construct(WordGroupProviderInterface $groupProvider)
    {
        $this->groupProvider = $groupProvider;
    }

    /**
     * Get word groups list and it's words.
     *
     * @Route("/api/word/group", methods={"GET"}, name="api_word_groups")
     */
    public function groupListWithWords(): JsonResponse
    {
        $groups = $this->groupProvider->getList();

        $json = [
            'items' => $groups->map(function ($item) {
                return [
                    'id' => $item->getId(),
                    'name' => $item->getName(),
                    'language' => $item->getLanguage()->getInfo(),
                    'translation' => $item->getTranslation()->getInfo(),
                    'words' => $item->getWords()->toArray(),
                ];
            }),
        ];

        return new JsonResponse($json);
    }

    /**
     * Get word groups list without words.
     *
     * @Route("/api/group", methods={"GET"}, name="api_groups")
     */
    public function groupList(Request $request): JsonResponse
    {
        $languageCode = $request->query->get('language');
        $translationCode = $request->query->get('translation');

        $groups = $this->groupProvider->getList(['language' => $languageCode, 'translation' => $translationCode]);

        $json = [
            'items' => $groups->map(function ($item) {
                return [
                    'id' => $item->getId(),
                    'name' => $item->getName(),
                    'language' => $item->getLanguage()->getInfo(),
                    'translation' => $item->getTranslation()->getInfo(),
                ];
            }),
        ];

        return new JsonResponse($json);
    }

    /**
     * Get word group by id.
     *
     * @Route("/api/group/{id}", methods={"GET"}, name="api_group")
     */
    public function groupItem(int $id): JsonResponse
    {
        $item = $this->groupProvider->getItem($id);

        $json = [
            'item' => [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'language' => $item->getLanguage()->getInfo(),
                'translation' => $item->getTranslation()->getInfo(),
                'words' => $item->getWords()->toArray(),
            ],
        ];

        return new JsonResponse($json);
    }

    /**
     * Delete word group.
     *
     * @Route("/api/group/{id}", methods={"DELETE"}, name="api_group_delete")
     */
    public function groupDelete(int $id, Request $request): JsonResponse
    {
        $response = new JsonResponse();
        $deleteWithData = $request->query->getBoolean('removeData');

        try {
            $this->groupProvider->removeItem((int) $id, $deleteWithData);
        } catch (EntityNotFoundException $e) {
            return $this->errorExit($response, $e->getMessage());
        }

        $json = [
            'status' => 'success',
            'message' => 'Word list successfully deleted.',
        ];

        $response->setData($json);

        return $response;
    }
}
