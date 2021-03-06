<?php

namespace App\Entity;

use App\Repository\LanguageRepository;
use App\ViewModel\LanguageViewModel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LanguageRepository::class)
 * @UniqueEntity("code")
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
     * @Assert\Length(min = 2, max = 2)
     */
    private string $code;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private string $name;

    /**
     * @ORM\OneToMany(targetEntity=Word::class, mappedBy="language", orphanRemoval=true)
     */
    private Collection $words;

    /**
     * @ORM\OneToMany(targetEntity=WordGroup::class, mappedBy="language")
     */
    private Collection $wordGroups;

    public function __construct(string $code, string $name)
    {
        $this->words = new ArrayCollection();
        $this->wordGroups = new ArrayCollection();
        $this->code = $code;
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getName(): ?string
    {
        return $this->name;
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

    public function getItem(): LanguageViewModel
    {
        return new LanguageViewModel(
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
