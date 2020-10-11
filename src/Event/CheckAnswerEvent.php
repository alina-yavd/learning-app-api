<?php

namespace App\Event;

use App\Entity\Word;
use App\Entity\WordTranslation;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Core\User\UserInterface;

class CheckAnswerEvent extends EventDispatcher
{
    private UserInterface $user;
    private Word $word;
    private WordTranslation $answer;
    private bool $passed;

    public function __construct(UserInterface $user, Word $word, WordTranslation $answer, bool $passed)
    {
        parent::__construct();
        $this->user = $user;
        $this->word = $word;
        $this->answer = $answer;
        $this->passed = $passed;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getWord(): Word
    {
        return $this->word;
    }

    public function getAnswer(): WordTranslation
    {
        return $this->answer;
    }

    public function isPassed(): bool
    {
        return $this->passed;
    }
}
