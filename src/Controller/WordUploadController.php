<?php

namespace App\Controller;

use App\Exception\UploadException;
use App\Service\WordsImporter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/upload")
 */
class WordUploadController extends ApiController
{
    private WordsImporter $uploader;

    public function __construct(WordsImporter $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * Upload word list.
     *
     * @Route(methods={"POST"})
     */
    public function index(Request $request): JsonResponse
    {
        $response = new JsonResponse();

        $originalCode = $request->request->get('original');
        $translationCode = $request->request->get('translation');
        $groupName = $request->request->get('group');
        $file = $request->files->get('file');

        if (!$originalCode || !$translationCode || empty($file)) {
            return $this->errorExit($response, sprintf('Required parameters: %s.', implode(', ', ['original', 'translation', 'file'])));
        }

        try {
            $this->uploader->upload($file, $originalCode, $translationCode, $groupName);
        } catch (UploadException $e) {
            return $this->errorExit($response, $e->getMessage());
        }

        $json = [
            'status' => 'success',
            'message' => 'Word list successfully uploaded.',
        ];

        $response->setData($json);

        return $response;
    }
}
