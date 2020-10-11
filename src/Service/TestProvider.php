<?php

namespace App\Service;

use App\Event\CheckAnswerEvent;
use App\Repository\WordRepository;
use App\Repository\WordTranslationRepository;
use App\ViewModel\TestViewModel;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Implements TestProviderInterface for entities that are stored in database.
 */
final class TestProvider implements TestProviderInterface
{
    private WordProviderInterface $wordProvider;
    private WordTranslationsProviderInterface $translationsProvider;
    private WordGroupProviderInterface $groupProvider;
    private RandomWordProviderInterface $randomWordProvider;
    private WordRepository $wordRepository;
    private WordTranslationRepository $wordTranslationRepository;
    private EventDispatcherInterface $dispatcher;

    public function __construct(
        WordProviderInterface $wordProvider,
        WordTranslationsProviderInterface $translationsProvider,
        WordGroupProviderInterface $groupProvider,
        RandomWordProviderInterface $randomWordProvider,
        WordRepository $wordRepository,
        WordTranslationRepository $wordTranslationRepository,
        EventDispatcherInterface $dispatcher
    ) {
        $this->wordProvider = $wordProvider;
        $this->translationsProvider = $translationsProvider;
        $this->groupProvider = $groupProvider;
        $this->randomWordProvider = $randomWordProvider;
        $this->wordRepository = $wordRepository;
        $this->wordTranslationRepository = $wordTranslationRepository;
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
        $translation = $this->translationsProvider->getItemForWord($word->getId());
        $answers = $this->translationsProvider->getListExcludingWord($word->getId());

        $answers->add($translation);
        $answers->shuffle();

        return new TestViewModel($word, $answers, $group);
    }

    /**
     * {@inheritdoc}
     */
    public function checkAnswer(int $wordId, int $answerId, ?UserInterface $user = null): bool
    {
        $answer = $this->translationsProvider->getItem($answerId);
        $answers = $this->translationsProvider->getItemsForWord($wordId);

        $passed = $answers->contains($answer);

        if ($user) {
            $word = $this->wordRepository->getById($wordId);
            $translation = $this->wordTranslationRepository->getById($answerId);
            $event = new CheckAnswerEvent($user, $word, $translation, $passed);
            $this->dispatcher->dispatch($event, 'tests.check_answer');
        }

        return $passed;
    }
}
