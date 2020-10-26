<?php

namespace App\Entity;

use App\Repository\UserProgressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserProgressRepository::class)
 */
class UserProgress
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="word")
     * @ORM\JoinColumn(nullable=false)
     */
    private UserInterface $user;

    /**
     * @ORM\ManyToOne(targetEntity=Word::class, inversedBy="progress")
     * @ORM\JoinColumn(nullable=false)
     */
    private Word $word;

    /**
     * @ORM\Column(type="integer")
     */
    private int $testCount;

    /**
     * @ORM\Column(type="integer")
     */
    private int $passCount;

    public function __construct(UserInterface $user, Word $word)
    {
        $this->user = $user;
        $this->word = $word;
        $this->testCount = 0;
        $this->passCount = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function getWord(): ?Word
    {
        return $this->word;
    }

    public function getTestCount(): int
    {
        return $this->testCount;
    }

    public function addTestCount(): void
    {
        ++$this->testCount;
    }

    public function getPassCount(): int
    {
        return $this->passCount;
    }

    public function addPassCount(): void
    {
        ++$this->passCount;
    }
}
