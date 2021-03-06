<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("games:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Name is required")
     * @Groups("games:read")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Description is required")
     * @Groups("games:read")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Price is required")
     * @Assert\Positive
     * @Groups("games:read")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Trailer Url is required")
     * @Groups("games:read")
     */
    private $trailerUrl;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Category is required")
     * @Groups("games:read")
     */
    private $category;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups("games:read")
     */
    private $rating;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("games:read")
     */
    private $picture;

    /**
     * @ORM\OneToMany(targetEntity=GameComment::class, mappedBy="game")
     */
    private $gameComments;

    /**
     * @ORM\OneToMany(targetEntity=Rating::class, mappedBy="game")
     */
    private $ratings;




    public function __construct()
    {
        $this->gameComments = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTrailerUrl(): ?string
    {
        return $this->trailerUrl;
    }

    public function setTrailerUrl(?string $trailerUrl): self
    {
        $this->trailerUrl = $trailerUrl;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection|GameComment[]
     */
    public function getGameComments(): Collection
    {
        return $this->gameComments;
    }

    public function addGameComment(GameComment $gameComment): self
    {
        if (!$this->gameComments->contains($gameComment)) {
            $this->gameComments[] = $gameComment;
            $gameComment->setGame($this);
        }

        return $this;
    }

    public function removeGameComment(GameComment $gameComment): self
    {
        if ($this->gameComments->removeElement($gameComment)) {
            // set the owning side to null (unless already changed)
            if ($gameComment->getGame() === $this) {
                $gameComment->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setGame($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getGame() === $this) {
                $rating->setGame(null);
            }
        }

        return $this;
    }



}