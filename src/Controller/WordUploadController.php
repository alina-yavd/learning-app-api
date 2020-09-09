<?php

namespace App\Controller;

use App\Exception\ApiException;
use App\Exception\UploadException;
use App\Service\WordsImporter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WordUploadController extends AbstractController
{
    private WordsImporter $uploader;

    public function __construct(WordsImporter $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * Upload word list.
     *
     * @Route("/api/upload", methods={"POST"}, name="api_words_upload")
     */
    public function index(Request $request): JsonResponse
    {
        $response = new JsonResponse();
        $response->headers->set('Access-Control-Allow-Origin', '*');

        $originalCode = $request->request->get('original');
        $translationCode = $request->request->get('translation');
        $groupName = $request->request->get('group');
        $file = $request->files->get('file');

        if (!$originalCode || !$translationCode || empty($file)) {
            $exception = new ApiException(406, 'Missing required parameters.');
            $response->setStatusCode(406);
            $response->setData($exception->getErrorDetails());

            return $response;
        }

        try {
            $this->uploader->upload($file, $originalCode, $translationCode, $groupName);
        } catch (UploadException $e) {
            $exception = new ApiException(406, $e->getMessage());
            $response->setStatusCode(406);
            $response->setData($exception->getErrorDetails());

            return $response;
        }

        $json = [
            'status' => 'success',
            'message' => 'Word list successfully uploaded.',
        ];

        $response->setData($json);

        return $response;
    }
}
