<?php

namespace App\Entity;

use App\Repository\WordGroupRepository;
use App\ViewModel\WordGroupDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WordGroupRepository::class)
 */
class WordGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\ManyToMany(targetEntity=Word::class, mappedBy="groups")
     */
    private Collection $words;

    /**
     * @ORM\ManyToOne(targetEntity=Language::class, inversedBy="wordGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private Language $language;

    /**
     * @ORM\ManyToOne(targetEntity=Language::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private Language $translation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $imageUrl;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $updatedAt;

    public function __construct()
    {
        $this->words = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function setLanguage(Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getTranslation(): Language
    {
        return $this->translation;
    }

    public function setTranslation(Language $translation): self
    {
        $this->translation = $translation;

        return $this;
    }

    /**
     * @return Collection|Word[]
     */
    public function getWords(): Collection
    {
        return $this->words;
    }

    public function addWords(Word $words): self
    {
        if (!$this->words->contains($words)) {
            $this->words[] = $words;
            $words->addToGroup($this);
        }

        return $this;
    }

    public function removeWords(Word $words): self
    {
        if ($this->words->contains($words)) {
            $this->words->removeElement($words);
            $words->removeFromGroup($this);
        }

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getItem(): WordGroupDTO
    {
        return new WordGroupDTO(
            $this->id,
            $this->name,
            $this->language,
            $this->translation,
            $this->getWords(),
            $this->imageUrl
        );
    }

    public function getInfo(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'language' => $this->getLanguage(),
            'translation' => $this->getTranslation(),
        ];
    }
}
