<?php

namespace App\Entity;

use App\Repository\LikesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LikesRepository::class)
 */
class Likes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $typeLike;

    /**
     * @ORM\ManyToOne(targetEntity=GameComment::class)
     */
    private $comment;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="likes")
     */
    private $idUser;

    public function __construct()
    {
        $this->idUser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeLike(): ?bool
    {
        return $this->typeLike;
    }

    public function setTypeLike(bool $typeLike): self
    {
        $this->typeLike = $typeLike;

        return $this;
    }

    public function getComment(): ?GameComment
    {
        return $this->comment;
    }

    public function setComment(?GameComment $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getIdUser(): Collection
    {
        return $this->idUser;
    }

    public function addIdUser(User $idUser): self
    {
        if (!$this->idUser->contains($idUser)) {
            $this->idUser[] = $idUser;
            $idUser->setLikes($this);
        }

        return $this;
    }

    public function removeIdUser(User $idUser): self
    {
        if ($this->idUser->removeElement($idUser)) {
            // set the owning side to null (unless already changed)
            if ($idUser->getLikes() === $this) {
                $idUser->setLikes(null);
            }
        }

        return $this;
    }
}
