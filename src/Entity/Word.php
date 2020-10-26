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
     * @ORM\ManyToMany(targetEntity=Word::class, mappedBy="translationWords")
     */
    private Collection $translations;

    /**
     * @ORM\ManyToMany(targetEntity="Word", inversedBy="translations")
     * @ORM\JoinTable(name="translations",
     *      joinColumns={@ORM\JoinColumn(name="word_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="translation_word_id", referencedColumnName="id")}
     *      )
     */
    private Collection $translationWords;

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
        $this->translationWords = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->progress = new ArrayCollection();
        $this->text = $text;
        $this->language = $language;
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
     * @return Collection|Word[]
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(Word $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations[] = $translation;
            $translation->addTranslationWord($this);
        }

        return $this;
    }

    public function removeTranslation(Word $translation): self
    {
        if ($this->translations->contains($translation)) {
            $this->translations->removeElement($translation);
            // set the owning side to null (unless already changed)
            if ($translation->getTranslationWords() === $this) {
                $translation->addTranslationWord(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Word[]
     */
    public function getTranslationWords(): Collection
    {
        return $this->translationWords;
    }

    public function addTranslationWord(Word $translationWord): self
    {
        if (!$this->translationWords->contains($translationWord)) {
            $this->translationWords[] = $translationWord;
            $translationWord->addTranslationWord($this);
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

    public function setLanguage(Language $language): self
    {
        $this->language = $language;

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

    public function getProgress(): Collection
    {
        return $this->progress;
    }

    public function getItem(): WordViewModel
    {
        return new WordViewModel(
            $this->id,
            $this->text,
            $this->language,
            $this->translations,
            $this->groups
        );
    }

    public function getInfo(): array
    {
        return [
            'id' => $this->getId(),
            'text' => $this->getText(),
            'language' => $this->getLanguage()->getCode(),
        ];
    }

    public function getInfoWithTranslation(?Language $language = null): array
    {
        $wordTranslations = (null === $language) ? $this->getTranslations() : $this->getTranslations()->filter(fn (Word $item) => $item->getLanguage() === $language);

        return [
            'id' => $this->getId(),
            'text' => $this->getText(),
            'language' => $this->getLanguage()->getCode(),
            'translations' => $wordTranslations->map(fn (Word $item) => $item->getInfo())->getValues(),
        ];
    }
}
