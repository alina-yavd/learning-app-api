<?php

namespace App\EventSubscriber;

use App\Entity\UserProgress;
use App\Event\CheckAnswerEvent;
use App\Repository\UserProgressRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CheckAnswerSubscriber implements EventSubscriberInterface
{
    private UserProgressRepository $progressRepository;

    public function __construct(UserProgressRepository $progressRepository)
    {
        $this->progressRepository = $progressRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            'tests.check_answer' => 'onCheckAnswer',
        ];
    }

    public function onCheckAnswer(CheckAnswerEvent $event)
    {
        $progress = $this->progressRepository->findOneBy(['user' => $event->getUser()->getId(), 'word' => $event->getWord()->getId()]);

        if (null === $progress) {
            $progress = new UserProgress($event->getUser(), $event->getWord());
        }

        $this->progressRepository->update($progress, $event->isPassed());
    }
}
