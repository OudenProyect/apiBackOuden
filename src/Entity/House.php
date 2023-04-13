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

    #[ORM\Column(length: 100, columnDefinition: "enum('To reform','In good condition')")]
    private ?string $state = null;

    #[ORM\Column]
    private ?int $m2 = null;

    #[ORM\Column(length: 100, columnDefinition: "enum('A','B','C','D','E','F','G','In process','External property','No data yet')")]
    private ?string $energy_consum = null;

    #[ORM\Column(nullable: true)]
    private ?int $year_construction = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(nullable: true)]
    private ?float $community_spend = null;

    #[ORM\ManyToMany(targetEntity: Feature::class, inversedBy: 'houses')]
    private Collection $feature;

    #[ORM\ManyToMany(targetEntity: LocationServices::class, inversedBy: 'houses')]
    private Collection $areaServices;

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

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

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

    public function getEnergyConsum(): ?string
    {
        return $this->energy_consum;
    }

    public function setEnergyConsum(string $energy_consum): self
    {
        $this->energy_consum = $energy_consum;

        return $this;
    }

    public function getYearConstruction(): ?int
    {
        return $this->year_construction;
    }

    public function setYearConstruction(?int $year_construction): self
    {
        $this->year_construction = $year_construction;

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

    public function getCommunitySpend(): ?float
    {
        return $this->community_spend;
    }

    public function setCommunitySpend(?float $community_spend): self
    {
        $this->community_spend = $community_spend;

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

    // public function getLoct(): ?Location
    // {
    //     return $this->loct;
    // }

    // public function setLoct(?Location $loct): self
    // {
    //     // unset the owning side of the relation if necessary
    //     if ($loct === null && $this->loct !== null) {
    //         $this->loct->setCasa(null);
    //     }

    //     // set the owning side of the relation if necessary
    //     if ($loct !== null && $loct->getCasa() !== $this) {
    //         $loct->setCasa($this);
    //     }

    //     $this->loct = $loct;

    //     return $this;
    // }

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
}
