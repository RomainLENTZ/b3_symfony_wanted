<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Hunt::class)]
    private Collection $hunts;

    #[ORM\ManyToMany(mappedBy: 'hunters', targetEntity: Hunt::class)]
    private Collection $myHunts;

    // #[ORM\ManyToMany(mappedBy: 'teammates', targetEntity: User::class)]
    // private Collection $teammates;

    #[ORM\OneToOne(mappedBy: 'owner', cascade: ['persist', 'remove'])]
    private ?Wallet $wallet = null;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'users')]
    private Collection $teammates;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'teammates')]
    private Collection $users;

    public function __construct()
    {
        $this->description = "";
        $this->wallet = new Wallet();
        $this->wallet->setOwner($this);
        $this->hunts = new ArrayCollection();
        $this->teammates = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, Hunt>
     */
    public function getHunts(): Collection
    {
        return $this->hunts;
    }

    public function addHunt(Hunt $hunt): self
    {
        if (!$this->hunts->contains($hunt)) {
            $this->hunts->add($hunt);
            $hunt->setAuthor($this);
        }

        return $this;
    }

    public function getMyHunts(): Collection
    {
        return $this->myHunts;
    }

    // public function getTeammates(): Collection
    // {
    //     return $this->teammates;
    // }

    public function addMyHunt(Hunt $hunt): self
    {
        if (!$this->myHunts->contains($hunt)) {
            $this->myHunts->add($hunt);
            $hunt->addHunter($this);
        }

        return $this;
    }

    // public function addTeammate(User $user): self
    // {
    //     if (!$this->teammates->contains($user)) {
    //         $this->teammates->add($user);
    //         $user->addTeammate($this);
    //     }

    //     return $this;
    // }

    public function removeHunt(Hunt $hunt): self
    {
        if ($this->hunts->removeElement($hunt)) {
            // set the owning side to null (unless already changed)
            if ($hunt->getAuthor() === $this) {
                $hunt->setAuthor(null);
            }
        }

        return $this;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(?Wallet $wallet): self
    {
        // unset the owning side of the relation if necessary
        if ($wallet === null && $this->wallet !== null) {
            $this->wallet->setOwner(null);
        }

        // set the owning side of the relation if necessary
        if ($wallet !== null && $wallet->getOwner() !== $this) {
            $wallet->setOwner($this);
        }

        $this->wallet = $wallet;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getTeammates(): Collection
    {
        return $this->teammates;
    }

    public function addTeammate(self $teammate): self
    {
        if (!$this->teammates->contains($teammate)) {
            $this->teammates->add($teammate);
        }

        return $this;
    }

    public function removeTeammate(self $teammate): self
    {
        $this->teammates->removeElement($teammate);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(self $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addTeammate($this);
        }

        return $this;
    }

    public function removeUser(self $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeTeammate($this);
        }

        return $this;
    }
}