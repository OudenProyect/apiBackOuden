<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'Companies')]
#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 9)]
    private ?string $Cif_company = null;

    #[ORM\Column(length: 100)]
    private ?string $Name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Description = null;

    #[ORM\Column(length: 255)]
    private ?string $Location = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $LinkWeb = null;

    #[ORM\Column]
    private ?int $phone = null;

    #[ORM\OneToOne(mappedBy: 'cif_company', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'Id_company', targetEntity: Publication::class, orphanRemoval: true)]
    private Collection $publications;

    public function __construct()
    {
        $this->publications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCifCompany(): ?string
    {
        return $this->Cif_company;
    }

    public function setCifCompany(string $Cif_company): self
    {
        $this->Cif_company = $Cif_company;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->Location;
    }

    public function setLocation(string $Location): self
    {
        $this->Location = $Location;

        return $this;
    }

    public function getLinkWeb(): ?string
    {
        return $this->LinkWeb;
    }

    public function setLinkWeb(?string $LinkWeb): self
    {
        $this->LinkWeb = $LinkWeb;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setCifCompany(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getCifCompany() !== $this) {
            $user->setCifCompany($this);
        }

        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Publication>
     */
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    public function addPublication(Publication $publication): self
    {
        if (!$this->publications->contains($publication)) {
            $this->publications->add($publication);
            $publication->setIdCompany($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): self
    {
        if ($this->publications->removeElement($publication)) {
            // set the owning side to null (unless already changed)
            if ($publication->getIdCompany() === $this) {
                $publication->setIdCompany(null);
            }
        }

        return $this;
    }
}
