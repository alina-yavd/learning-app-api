<?php

namespace App\Entity;

use App\Repository\UserLearningRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserLearningRepository::class)
 */
class UserLearning
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="learning", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private UserInterface $user;

    /**
     * @ORM\ManyToMany(targetEntity=WordGroup::class)
     */
    private Collection $wordGroups;

    /**
     * @ORM\ManyToMany(targetEntity=Language::class)
     */
    private Collection $languages;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
        $this->wordGroups = new ArrayCollection();
        $this->languages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * @return Collection|WordGroup[]
     */
    public function getWordGroups(): Collection
    {
        return $this->wordGroups;
    }

    public function addWordGroup(WordGroup $wordGroup): void
    {
        if (!$this->wordGroups->contains($wordGroup)) {
            $this->wordGroups[] = $wordGroup;
        }
    }

    public function removeWordGroups(): void
    {
        foreach ($this->wordGroups as $group) {
            $this->wordGroups->removeElement($group);
        }
    }

    /**
     * @return Collection|Language[]
     */
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    public function addLanguage(Language $language): void
    {
        if (!$this->languages->contains($language)) {
            $this->languages[] = $language;
        }
    }

    public function removeLanguages(): void
    {
        foreach ($this->languages as $language) {
            $this->languages->removeElement($language);
        }
    }
}
