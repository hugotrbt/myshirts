<?php

namespace App\Entity;

use App\Repository\StadiumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StadiumRepository::class)]
class Stadium
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: 'boolean')]
    private bool $published = false;

    #[ORM\ManyToOne(targetEntity: Member::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Member $creator = null;

    /**
     * @var Collection<int, Shirt>
     */
    #[ORM\ManyToMany(targetEntity: Shirt::class, inversedBy: 'stadia')]
    private Collection $shirts;

    public function __construct()
    {
        $this->shirts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }


    public function isPublished(): bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): static
    {
        $this->published = $published;
        return $this;
    }

    public function getCreator(): ?Member
    {
        return $this->creator;
    }

    public function setCreator(?Member $creator): static
    {
        $this->creator = $creator;
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
        }

        return $this;
    }

    public function removeShirt(Shirt $shirt): static
    {
        $this->shirts->removeElement($shirt);

        return $this;
    }
}
