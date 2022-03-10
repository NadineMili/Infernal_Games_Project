<?php

namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RatingRepository::class)
 */
class Rating
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $numStar;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumStar(): ?int
    {
        return $this->numStar;
    }

    public function setNumStar(int $numStar): self
    {
        $this->numStar = $numStar;

        return $this;
    }
}
