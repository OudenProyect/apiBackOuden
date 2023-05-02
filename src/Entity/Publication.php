<?php

namespace App\Entity;

use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'Publications')]
#[ORM\Entity(repositoryClass: PublicationRepository::class)]
class Publication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $hour = null;

    #[ORM\ManyToOne(inversedBy: 'publications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $Id_company = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'favorit_publications')]
    private Collection $users_favorit;

    #[ORM\OneToMany(mappedBy: 'publication', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'publication', targetEntity: Video::class)]
    private Collection $videos;

    #[ORM\OneToMany(mappedBy: 'publication', targetEntity: Image::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $images;

    #[ORM\OneToOne(targetEntity: House::class, cascade: ['persist', 'remove'])]
    private ?House $house = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $details = null;

    public function __construct()
    {
        $this->users_favorit = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getHour(): ?\DateTimeInterface
    {
        return $this->hour;
    }

    public function setHour(\DateTimeInterface $hour): self
    {
        $this->hour = $hour;

        return $this;
    }

    public function getIdCompany(): ?Company
    {
        return $this->Id_company;
    }

    public function setIdCompany(?Company $Id_company): self
    {
        $this->Id_company = $Id_company;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsersFavorit(): Collection
    {
        return $this->users_favorit;
    }

    public function addUsersFavorit(User $usersFavorit): self
    {
        if (!$this->users_favorit->contains($usersFavorit)) {
            $this->users_favorit->add($usersFavorit);
            $usersFavorit->addFavoritPublication($this);
        }

        return $this;
    }

    public function removeUsersFavorit(User $usersFavorit): self
    {
        if ($this->users_favorit->removeElement($usersFavorit)) {
            $usersFavorit->removeFavoritPublication($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setPublication($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPublication() === $this) {
                $comment->setPublication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Video>
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos->add($video);
            $video->setPublication($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getPublication() === $this) {
                $video->setPublication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setPublication($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getPublication() === $this) {
                $image->setPublication(null);
            }
        }

        return $this;
    }

    public function getHouse(): ?House
    {
        return $this->house;
    }

    public function setHouse(?House $house): self
    {
        $this->house = $house;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): self
    {
        $this->details = $details;

        return $this;
    }
}
