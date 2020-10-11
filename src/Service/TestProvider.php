<?php

namespace App\Service;

use App\Entity\WordTranslation;
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
    const ANSWERS_COUNT = 3;
    private WordTranslationsProviderInterface $translationsProvider;
    private WordGroupProviderInterface $groupProvider;
    private RandomWordProviderInterface $randomWordProvider;
    private WordRepository $wordRepository;
    private WordTranslationRepository $wordTranslationRepository;
    private WordFilter $filter;
    private EventDispatcherInterface $dispatcher;

    public function __construct(
        WordTranslationsProviderInterface $translationsProvider,
        WordGroupProviderInterface $groupProvider,
        RandomWordProviderInterface $randomWordProvider,
        WordRepository $wordRepository,
        WordTranslationRepository $wordTranslationRepository,
        WordFilter $filter,
        EventDispatcherInterface $dispatcher
    ) {
        $this->translationsProvider = $translationsProvider;
        $this->groupProvider = $groupProvider;
        $this->randomWordProvider = $randomWordProvider;
        $this->wordRepository = $wordRepository;
        $this->wordTranslationRepository = $wordTranslationRepository;
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
            $translationLang = $group->getTranslation();
            $this->filter->setLanguage($translationLang);
        } else {
            $group = null;
            $translationLang = null;
        }

        $word = $this->randomWordProvider->getItem($group);
        $translations = $word->getTranslations()->filter(fn (WordTranslation $item) => $item->getLanguage() === $translationLang);
        $translation = $translations->first()->getItem();

        $this->filter->setExcludeId($word->getId());

        $answers = $this->translationsProvider->getRandomList(self::ANSWERS_COUNT, $this->filter);

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
