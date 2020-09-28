<?php

namespace App\Service;

use App\Collection\Languages;
use App\Entity\Language;
use App\Exception\LanguageAlreadyExistsException;
use App\Repository\LanguageRepository;
use App\ViewModel\LanguageViewModel;
use InvalidArgumentException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Implements LanguageServiceInterface for entities that are stored in database.
 */
final class LanguageService implements LanguageServiceInterface
{
    private LanguageRepository $repository;
    private ValidatorInterface $validator;

    public function __construct(LanguageRepository $languageRepository, ValidatorInterface $validator)
    {
        $this->repository = $languageRepository;
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(): Languages
    {
        $items = $this->repository->findAll();

        $viewModels = \array_map(fn (Language $item) => $item->getItem(), $items);

        return new Languages(...$viewModels);
    }

    /**
     * {@inheritdoc}
     */
    public function createItem(string $code, string $name): LanguageViewModel
    {
        $item = $this->repository->findOneBy(['code' => $code]);

        if (null !== $item) {
            throw new LanguageAlreadyExistsException(sprintf('Language with code "%s" already exists.', $code));
        }

        $language = new Language($code, $name);

        $errors = $this->validator->validate($language);
        if (count($errors) > 0) {
            throw new InvalidArgumentException((string) $errors);
        }

        $this->repository->create($language);

        return $language->getItem();
    }
}
