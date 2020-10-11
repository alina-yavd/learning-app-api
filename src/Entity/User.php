<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $lastName;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $registeredAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\OneToMany(targetEntity=UserProgress::class, mappedBy="user", orphanRemoval=true)
     */
    private Collection $progress;

    /**
     * @ORM\OneToOne(targetEntity=UserLearning::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private ?UserLearning $learning;

    public function __construct(string $email)
    {
        $this->email = $email;
        $this->registeredAt = new \DateTimeImmutable();
        $this->progress = new ArrayCollection();
        $this->learning = null;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $userRoles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $userRoles[] = 'ROLE_USER';

        return array_unique($userRoles);
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeImmutable
    {
        return $this->registeredAt;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|UserProgress[]
     */
    public function getProgress(): Collection
    {
        return $this->progress;
    }

    public function addProgress(UserProgress $progress): self
    {
        if (!$this->progress->contains($progress)) {
            $this->progress[] = $progress;
        }

        return $this;
    }

    public function removeProgress(UserProgress $progress): self
    {
        if ($this->progress->contains($progress)) {
            $this->progress->removeElement($progress);
        }

        return $this;
    }

    public function getLearning(): ?UserLearning
    {
        return $this->learning;
    }

    public function setLearning(UserLearning $learning): self
    {
        $this->learning = $learning;

        // set the owning side of the relation if necessary
        if ($learning->getUser() !== $this) {
            $learning->setUser($this);
        }

        return $this;
    }
}
