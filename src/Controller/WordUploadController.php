<?php

namespace App\Controller;

use App\Exception\UploadException;
use App\Service\WordsImporter;
use App\ViewModel\UploadedWordListDTO;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/upload")
 */
class WordUploadController extends ApiController
{
    private WordsImporter $uploader;
    private ValidatorInterface $validator;

    public function __construct(WordsImporter $uploader, ValidatorInterface $validator)
    {
        $this->uploader = $uploader;
        $this->validator = $validator;
    }

    /**
     * Upload word list.
     *
     * @Route(methods={"POST"})
     */
    public function index(Request $request): JsonResponse
    {
        $response = new JsonResponse();

        $wordList = new UploadedWordListDTO($request->request, $request->files);

        $errors = $this->validator->validate($wordList);
        if (count($errors) > 0) {
            throw new InvalidArgumentException((string) $errors);
        }

        try {
            $this->uploader->upload($wordList);
        } catch (UploadException $e) {
            return $this->errorExit($response, $e->getMessage());
        }

        return $this->successExit($response);
    }
}
