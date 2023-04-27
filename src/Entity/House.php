<?php

namespace App\Entity;

use App\Repository\HouseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'Houses')]
#[ORM\Entity(repositoryClass: HouseRepository::class)]
class House
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, columnDefinition: "enum('Rustic Property','Castle','Palace','Country house','Town House','Tower','Mansion')")]
    private ?string $type = null;

    #[ORM\Column]
    private ?int $n_bedrooms = null;

    #[ORM\Column]
    private ?int $toilets = null;

    #[ORM\Column]
    private ?int $m2 = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\ManyToMany(targetEntity: Feature::class, inversedBy: 'houses')]
    private Collection $feature;

    #[ORM\ManyToMany(targetEntity: LocationServices::class, inversedBy: 'houses')]
    private Collection $areaServices;

    #[ORM\Column(length: 20)]
    private ?string $UsefulLivinArea = null;

    #[ORM\Column(length: 20)]
    private ?string $BuildedSurface = null;

    #[ORM\Column]
    private ?int $floors = null;

    #[ORM\Column]
    private ?int $parkingSpace = null;

    // #[ORM\OneToOne(targetEntity: Location::class, inversedBy: 'house')]
    // private ?Location $location = null;

    public function __construct()
    {
        $this->feature = new ArrayCollection();
        $this->areaServices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNBedrooms(): ?int
    {
        return $this->n_bedrooms;
    }

    public function setNBedrooms(int $n_bedrooms): self
    {
        $this->n_bedrooms = $n_bedrooms;

        return $this;
    }

    public function getToilets(): ?int
    {
        return $this->toilets;
    }

    public function setToilets(int $toilets): self
    {
        $this->toilets = $toilets;

        return $this;
    }


    public function getM2(): ?int
    {
        return $this->m2;
    }

    public function setM2(int $m2): self
    {
        $this->m2 = $m2;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }


    /**
     * @return Collection<int, Feature>
     */
    public function getFeature(): Collection
    {
        return $this->feature;
    }

    public function addFeature(Feature $feature): self
    {
        if (!$this->feature->contains($feature)) {
            $this->feature->add($feature);
        }

        return $this;
    }

    public function removeFeature(Feature $feature): self
    {
        $this->feature->removeElement($feature);

        return $this;
    }

    /**
     * @return Collection<int, LocationServices>
     */
    public function getAreaServices(): Collection
    {
        return $this->areaServices;
    }

    public function addAreaService(LocationServices $areaService): self
    {
        if (!$this->areaServices->contains($areaService)) {
            $this->areaServices->add($areaService);
        }

        return $this;
    }

    public function removeAreaService(LocationServices $areaService): self
    {
        $this->areaServices->removeElement($areaService);

        return $this;
    }

    public function getUsefulLivinArea(): ?string
    {
        return $this->UsefulLivinArea;
    }

    public function setUsefulLivinArea(string $UsefulLivinArea): self
    {
        $this->UsefulLivinArea = $UsefulLivinArea;

        return $this;
    }

    public function getBuildedSurface(): ?string
    {
        return $this->BuildedSurface;
    }

    public function setBuildedSurface(string $BuildedSurface): self
    {
        $this->BuildedSurface = $BuildedSurface;

        return $this;
    }

    public function getFloors(): ?int
    {
        return $this->floors;
    }

    public function setFloors(int $floors): self
    {
        $this->floors = $floors;

        return $this;
    }

    public function getParkingSpace(): ?int
    {
        return $this->parkingSpace;
    }

    public function setParkingSpace(int $parkingSpace): self
    {
        $this->parkingSpace = $parkingSpace;

        return $this;
    }
}
