<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class Member implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToOne(targetEntity: LockerRoom::class, cascade: ['persist', 'remove'], inversedBy: 'member')]
    #[ORM\JoinColumn(nullable: false)]
    private ?LockerRoom $lockerRoom = null;

    #[ORM\OneToMany(targetEntity: Stadium::class, mappedBy: 'creator')]
    private Collection $stadia;

    public function __construct()
    {
        $this->stadia = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLockerRoom(): ?LockerRoom
    {
        return $this->lockerRoom;
    }

    public function setLockerRoom(LockerRoom $lockerRoom): static
    {
        $this->lockerRoom = $lockerRoom;

        return $this;
    }

    /**
     * @return Collection<int, Stadium>
     */
    public function getStadia(): Collection
    {
        return $this->stadia;
    }

    public function addStadium(Stadium $stadium): static
    {
        if (!$this->stadia->contains($stadium)) {
            $this->stadia->add($stadium);
            $stadium->setCreator($this);
        }

        return $this;
    }

    public function removeStadium(Stadium $stadium): static
    {
        if ($this->stadia->removeElement($stadium)) {
            // Set the owning side to null (unless already changed)
            if ($stadium->getCreator() === $this) {
                $stadium->setCreator(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->id ?? '';  // Retourne le nom de l'équipe (team) ou une chaîne vide si c'est null
    }

}
