<?php

namespace App\Entity;

use App\Repository\ShirtRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShirtRepository::class)]
#[Vich\Uploadable]
class Shirt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $team = null;

    #[ORM\ManyToOne(inversedBy: 'shirts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?LockerRoom $lockerRoom = null;

    /**
     * @var Collection<int, Stadium>
     */
    #[ORM\ManyToMany(targetEntity: Stadium::class, mappedBy: 'shirts')]
    private Collection $stadia;

    #[Vich\UploadableField(mapping: 'shirts', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $contentType = null;

    public function __construct()
    {
        $this->stadia = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeam(): ?string
    {
        return $this->team;
    }

    public function setTeam(string $team): static
    {
        $this->team = $team;

        return $this;
    }

    public function getLockerRoom(): ?LockerRoom
    {
        return $this->lockerRoom;
    }

    public function setLockerRoom(?LockerRoom $lockerRoom): static
    {
        $this->lockerRoom = $lockerRoom;

        return $this;
    }
    
    public function __toString(): string
    {
        return $this->team ?? '';  // Retourne le nom de l'équipe (team) ou une chaîne vide si c'est null
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
            $stadium->addShirt($this);
        }

        return $this;
    }

    public function removeStadium(Stadium $stadium): static
    {
        if ($this->stadia->removeElement($stadium)) {
            $stadium->removeShirt($this);
        }

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function setContentType(?string $contentType): void
    {
    $this->contentType = $contentType;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

}
