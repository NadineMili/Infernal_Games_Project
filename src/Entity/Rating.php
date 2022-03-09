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
    private $rateIndex;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRateIndex(): ?int
    {
        return $this->rateIndex;
    }

    public function setRateIndex(int $rateIndex): self
    {
        $this->rateIndex = $rateIndex;

        return $this;
    }
}
