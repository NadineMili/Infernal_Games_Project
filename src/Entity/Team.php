<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $countMembers;

    /**
     * @ORM\Column(type="boolean")
     */
    private $eliminated;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $winner;

    /**
     * @ORM\ManyToOne(targetEntity=Tournament::class, inversedBy="teams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournament;

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

    public function getCountMembers(): ?int
    {
        return $this->countMembers;
    }

    public function setCountMembers(int $countMembers): self
    {
        $this->countMembers = $countMembers;

        return $this;
    }

    public function getEliminated(): ?bool
    {
        return $this->eliminated;
    }

    public function setEliminated(bool $eliminated): self
    {
        $this->eliminated = $eliminated;

        return $this;
    }

    public function getWinner(): ?bool
    {
        return $this->winner;
    }

    public function setWinner(?bool $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }
}
