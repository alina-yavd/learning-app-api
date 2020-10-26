<?php

namespace App\Event;

use App\Entity\Word;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Core\User\UserInterface;

class CheckAnswerEvent extends EventDispatcher
{
    private UserInterface $user;
    private Word $word;
    private Word $answer;
    private bool $passed;

    public function __construct(UserInterface $user, Word $word, Word $answer, bool $passed)
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

    public function getAnswer(): Word
    {
        return $this->answer;
    }

    public function isPassed(): bool
    {
        return $this->passed;
    }
}
