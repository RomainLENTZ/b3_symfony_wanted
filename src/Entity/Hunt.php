<?php

namespace App\Entity;

use App\Repository\HuntRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HuntRepository::class)]
class Hunt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'hunts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'hunts')]
    private Collection $hunters;

    #[ORM\Column]
    private ?int $bounty = null;

    public function __construct()
    {
        $this->hunters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getHunters(): Collection
    {
        return $this->hunters;
    }

    public function addHunter(User $hunter): self
    {
        if (!$this->hunters->contains($hunter)) {
            $this->hunters->add($hunter);
        }

        return $this;
    }

    public function removeHunter(User $hunter): self
    {
        $this->hunters->removeElement($hunter);

        return $this;
    }

    public function getBounty(): ?int
    {
        return $this->bounty;
    }

    public function setBounty(int $bounty): self
    {
        $this->bounty = $bounty;

        return $this;
    }
}
