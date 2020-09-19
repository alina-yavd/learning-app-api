<?php

namespace App\Entity;

use App\Repository\LanguageRepository;
use App\ViewModel\LanguageDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LanguageRepository::class)
 */
class Language
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private string $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\OneToMany(targetEntity=Word::class, mappedBy="language", orphanRemoval=true)
     */
    private Collection $words;

    /**
     * @ORM\OneToMany(targetEntity=WordTranslation::class, mappedBy="language", orphanRemoval=true)
     */
    private Collection $translations;

    /**
     * @ORM\OneToMany(targetEntity=WordGroup::class, mappedBy="language")
     */
    private Collection $wordGroups;

    public function __construct()
    {
        $this->words = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->wordGroups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Word[]
     */
    public function getWords(): ?Collection
    {
        return $this->words;
    }

    public function addWord(Word $word): self
    {
        if (!$this->words->contains($word)) {
            $this->words[] = $word;
            $word->setLanguage($this);
        }

        return $this;
    }

    public function removeWord(Word $word): self
    {
        if ($this->words->contains($word)) {
            $this->words->removeElement($word);
            // set the owning side to null (unless already changed)
            if ($word->getLanguage() === $this) {
                $word->setLanguage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|WordTranslation[]
     */
    public function getTranslations(): ?Collection
    {
        return $this->translations;
    }

    public function addTranslation(WordTranslation $wordTranslation): self
    {
        if (!$this->translations->contains($wordTranslation)) {
            $this->translations[] = $wordTranslation;
            $wordTranslation->setLanguage($this);
        }

        return $this;
    }

    public function removeTranslation(WordTranslation $wordTranslation): self
    {
        if ($this->translations->contains($wordTranslation)) {
            $this->translations->removeElement($wordTranslation);
            // set the owning side to null (unless already changed)
            if ($wordTranslation->getLanguage() === $this) {
                $wordTranslation->setLanguage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|WordGroup[]
     */
    public function getWordGroups(): Collection
    {
        return $this->wordGroups;
    }

    public function addWordGroup(WordGroup $wordGroup): self
    {
        if (!$this->wordGroups->contains($wordGroup)) {
            $this->wordGroups[] = $wordGroup;
            $wordGroup->setLanguage($this);
        }

        return $this;
    }

    public function removeWordGroup(WordGroup $wordGroup): self
    {
        if ($this->wordGroups->contains($wordGroup)) {
            $this->wordGroups->removeElement($wordGroup);
            // set the owning side to null (unless already changed)
            if ($wordGroup->getLanguage() === $this) {
                $wordGroup->setLanguage(null);
            }
        }

        return $this;
    }

    public function getItem(): LanguageDTO
    {
        return new LanguageDTO(
            $this->id,
            $this->code,
            $this->name
        );
    }

    public function getInfo(): array
    {
        return [
            'id' => $this->getId(),
            'code' => $this->getCode(),
            'name' => $this->getName(),
        ];
    }
}
