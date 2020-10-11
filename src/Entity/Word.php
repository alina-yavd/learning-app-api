<?php

namespace App\Entity;

use App\Repository\WordRepository;
use App\ViewModel\WordViewModel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WordRepository::class)
 */
class Word
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
    private string $text;

    /**
     * @ORM\OneToMany(targetEntity=WordTranslation::class, mappedBy="word", orphanRemoval=true)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private Collection $translations;

    /**
     * @ORM\ManyToMany(targetEntity=WordGroup::class, inversedBy="words")
     */
    private Collection $groups;

    /**
     * @ORM\ManyToOne(targetEntity=Language::class, inversedBy="words")
     * @ORM\JoinColumn(nullable=false)
     */
    private Language $language;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=UserProgress::class, mappedBy="word", orphanRemoval=true)
     */
    private Collection $progress;

    public function __construct(string $text, Language $language)
    {
        $this->translations = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->text = $text;
        $this->language = $language;
        $this->progress = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return Collection|WordTranslation[]
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(WordTranslation $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations[] = $translation;
            $translation->setWord($this);
        }

        return $this;
    }

    public function removeTranslation(WordTranslation $translation): self
    {
        if ($this->translations->contains($translation)) {
            $this->translations->removeElement($translation);
            // set the owning side to null (unless already changed)
            if ($translation->getWord() === $this) {
                $translation->setWord(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|WordGroup[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addToGroup(WordGroup $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
        }

        return $this;
    }

    public function getLanguage(): Language
    {
        return $this->language;
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

    public function getProgress(): Collection
    {
        return $this->progress;
    }

    public function getItem(): WordViewModel
    {
        return new WordViewModel(
            $this->id,
            $this->text,
            $this->translations,
            $this->groups
        );
    }

    public function getInfo(): array
    {
        return [
            'id' => $this->getId(),
            'text' => $this->getText(),
        ];
    }
}
