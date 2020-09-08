<?php

namespace App\Entity;

use App\Repository\WordRepository;
use App\ViewModel\WordDTO;
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

    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
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

    public function removeFromGroup(WordGroup $group): self
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
        }

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

    public function getItem(): WordDTO
    {
        return new WordDTO(
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

    public function getTranslationsInfo(): array
    {
        $translationsInfo = $this->getTranslations() ? $this->getTranslations()->map(function ($item) {
            return $item->getInfo();
        }) : null;

        return array_merge($this->getInfo(), ['translations' => $translationsInfo]);
    }

    public function getFullInfo(): array
    {
        $fullInfo = ['groups' => $this->getGroups() ? $this->getGroups()->map(function ($item) {
            return $item->getInfo();
        }) : null];

        return array_merge($this->getTranslationsInfo(), $fullInfo);
    }
}
