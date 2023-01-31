<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'Locations')]
#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\Column(length: 255)]
    private ?string $name_via = null;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\Column(length: 100)]
    private ?string $region = null;

    #[ORM\Column(length: 100)]
    private ?string $province = null;

    #[ORM\Column(length: 5)]
    private ?string $postal_code = null;

    #[ORM\OneToOne(mappedBy: 'location', cascade: ['persist', 'remove'])]
    private ?Publication $publication = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameVia(): ?string
    {
        return $this->name_via;
    }

    public function setNameVia(string $name_via): self
    {
        $this->name_via = $name_via;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(string $province): self
    {
        $this->province = $province;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getPublication(): ?Publication
    {
        return $this->publication;
    }

    public function setPublication(Publication $publication): self
    {
        // set the owning side of the relation if necessary
        if ($publication->getLocation() !== $this) {
            $publication->setLocation($this);
        }

        $this->publication = $publication;

        return $this;
    }
}
