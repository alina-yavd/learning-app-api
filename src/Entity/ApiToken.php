<?php

namespace App\Entity;

use App\Repository\ApiTokenRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApiTokenRepository::class)
 */
class ApiToken
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $token;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @ORM\OneToOne(targetEntity=ApiRefreshToken::class, mappedBy="token", cascade={"persist", "remove"})
     */
    private ?ApiRefreshToken $refreshToken;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private \DateTimeImmutable $expiresAt;

    public function __construct(User $user)
    {
        $this->token = bin2hex(random_bytes(40));
        $this->user = $user;
        $createdAt = new \DateTimeImmutable();
        $expiresAt = $createdAt->add(new \DateInterval('PT1H'));
        $this->expiresAt = $expiresAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getRefreshToken(): ?ApiRefreshToken
    {
        return $this->refreshToken;
    }

    public function getExpiresAt(): \DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function isExpired(): bool
    {
        return $this->getExpiresAt() <= new \DateTimeImmutable();
    }
}
