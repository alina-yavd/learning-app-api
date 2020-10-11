<?php

namespace App\Service;

use App\Entity\Word;
use App\Event\CheckAnswerEvent;
use App\Exception\EntityNotFoundException;
use App\Repository\WordRepository;
use App\ViewModel\TestViewModel;
use App\ViewModel\WordGroupViewModel;
use App\ViewModel\WordViewModel;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Implements TestProviderInterface for entities that are stored in database.
 */
final class TestProvider implements TestProviderInterface
{
    const ANSWERS_COUNT = 3;
    private WordGroupProviderInterface $groupProvider;
    private WordProviderInterface $wordProvider;
    private RandomWordProviderInterface $randomWordProvider;
    private WordRepository $wordRepository;
    private WordFilter $filter;
    private EventDispatcherInterface $dispatcher;

    public function __construct(
        WordGroupProviderInterface $groupProvider,
        WordProviderInterface $wordProvider,
        RandomWordProviderInterface $randomWordProvider,
        WordRepository $wordRepository,
        WordFilter $filter,
        EventDispatcherInterface $dispatcher
    ) {
        $this->groupProvider = $groupProvider;
        $this->wordProvider = $wordProvider;
        $this->randomWordProvider = $randomWordProvider;
        $this->wordRepository = $wordRepository;
        $this->filter = $filter;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function getTest(?int $groupId = null): ?TestViewModel
    {
        if ($groupId) {
            $group = $this->groupProvider->getItem($groupId);
        } else {
            $group = null;
        }

        $word = $this->randomWordProvider->getItem($group);
        $translation = $this->getTranslation($word, $group);

        $this->filter->setLanguage($translation->getLanguage());
        $this->filter->setExcludeIds([$word->getId(), $translation->getId()]);

        $answers = $this->randomWordProvider->getRandomList(self::ANSWERS_COUNT, $this->filter);
        $answers->add($translation);
        $answers->shuffle();

        return new TestViewModel($word, $answers, $group);
    }

    /**
     * {@inheritdoc}
     */
    public function checkAnswer(int $wordId, int $answerId, ?UserInterface $user = null): bool
    {
        $answer = $this->wordProvider->getEntity($answerId);
        $answers = $this->wordProvider->getEntity($wordId);

        $passed = $answers->getTranslations()->contains($answer);

        if ($user) {
            $word = $this->wordRepository->getById($wordId);
            $translation = $this->wordRepository->getById($answerId);
            $event = new CheckAnswerEvent($user, $word, $translation, $passed);
            $this->dispatcher->dispatch($event, 'tests.check_answer');
        }

        return $passed;
    }

    protected function getTranslation(WordViewModel $word, ?WordGroupViewModel $group): WordViewModel
    {
        $translations = $word->getTranslations();

        if (null !== $group) {
            $translations = $translations->filter(fn (Word $item) => $item->getLanguage() === $group->getTranslation());
        } else {
            $translations = $translations->filter(fn (Word $item) => $item->getLanguage() !== $word->getLanguage());
        }

        $keys = $translations->getKeys();
        $randomKey = \array_rand($keys);
        $translation = $translations->get($keys[$randomKey])->getItem();

        if (null === $translations) {
            throw EntityNotFoundException::general('Word');
        }

        return $translation;
    }
}
