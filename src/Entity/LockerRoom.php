<?php

namespace App\Entity;

use App\Entity\Member;
use App\Repository\LockerRoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LockerRoomRepository::class)]
class LockerRoom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @var Collection<int, Shirt>
     */
    #[ORM\OneToMany(targetEntity: Shirt::class, mappedBy: 'lockerRoom', orphanRemoval: true, cascade:["persist"])]
    private Collection $shirts;

    #[ORM\OneToOne(targetEntity: Member::class, mappedBy: 'lockerRoom')]
    private ?Member $member = null;

    public function __construct()
    {
        $this->shirts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Shirt>
     */
    public function getShirts(): Collection
    {
        return $this->shirts;
    }

    public function addShirt(Shirt $shirt): static
    {
        if (!$this->shirts->contains($shirt)) {
            $this->shirts->add($shirt);
            $shirt->setLockerRoom($this);
        }

        return $this;
    }

    public function removeShirt(Shirt $shirt): static
    {
        if ($this->shirts->removeElement($shirt)) {
            // set the owning side to null (unless already changed)
            if ($shirt->getLockerRoom() === $this) {
                $shirt->setLockerRoom(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->title ?? '';
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(Member $member): self
    {
        $this->member = $member;
        return $this;
    }

}
