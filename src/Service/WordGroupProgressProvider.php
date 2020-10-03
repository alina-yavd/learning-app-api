<?php

namespace App\Service;

use App\Entity\UserProgress;
use App\Entity\WordGroup;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;

class WordGroupProgressProvider implements WordGroupProgressProviderInterface
{
    const WORD_LEARNED_PASS_COUNT = 6;
    private Collection $progress;
    private WordGroup $group;

    public function getProgress(UserInterface $user, WordGroup $group): float
    {
        $this->progress = $user->getProgress();
        $this->group = $group;

        $groupProgress = $this->getGroupProgress();
        $points = $this->group->getWords()->count() * self::WORD_LEARNED_PASS_COUNT;

        $wordsScores = $groupProgress->map(function (UserProgress $item) {
            return $item->getPassCount();
        });
        $score = array_sum($wordsScores->toArray());

        return $score * 100 / $points;
    }

    protected function getGroupProgress()
    {
        $groupWords = $this->group->getWords();

        return $this->progress->filter(function (UserProgress $item) use ($groupWords) {
            return $groupWords->contains($item->getWord());
        });
    }
}
