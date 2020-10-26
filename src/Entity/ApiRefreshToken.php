<?php

namespace App\Entity;

use App\Repository\ApiRefreshTokenRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApiRefreshTokenRepository::class)
 */
class ApiRefreshToken
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\OneToOne(targetEntity=ApiToken::class, inversedBy="refreshToken", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ApiToken $token;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $refreshToken;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $expiresAt;

    public function __construct(ApiToken $token)
    {
        $this->token = $token;
        $this->refreshToken = bin2hex(random_bytes(40));
        $createdAt = new \DateTimeImmutable();
        $expiresAt = $createdAt->add(new \DateInterval('PT24H'));
        $this->expiresAt = $expiresAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?ApiToken
    {
        return $this->token;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function isExpired(): bool
    {
        return $this->getExpiresAt() <= new \DateTimeImmutable();
    }
}
