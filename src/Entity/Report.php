<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReportRepository::class)
 */
class Report
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="reports")
     */
    private $relation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRelation(): ?user
    {
        return $this->relation;
    }

    public function setRelation(?user $relation): self
    {
        $this->relation = $relation;

        return $this;
    }
}
