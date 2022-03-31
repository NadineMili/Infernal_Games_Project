<?php

namespace App\Entity;

use App\Repository\GameCommentRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GameCommentRepository::class)
 */
class GameComment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="gameComments")
     * @Groups("gameComments:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5000)
     * @Groups("gameComments:read")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="gameComment")
     * @Groups("gameComments:read")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="gameComments")
     * @Groups("gameComments:read")
     */
    private $game;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }



    /**
     * @return Collection<int, Game>
     */


    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

}