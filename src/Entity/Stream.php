<?php

namespace App\Entity;

use App\Repository\StreamRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=StreamRepository::class)
 */
class Stream
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank(message="Title can't be blank!")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Describe your stream!")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=StreamRating::class, inversedBy="streams")
     * @Assert\NotBlank(message="Maturity rating is required!")
     */
    private $rating;

    /**
     * @ORM\ManyToOne(targetEntity=StreamCategory::class, inversedBy="streams")
     * @Assert\NotBlank(message="What is your stream about?")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=StreamData::class, inversedBy="streams")
     */
    private $accessData;

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

    public function getRating(): ?StreamRating
    {
        return $this->rating;
    }

    public function setRating(?StreamRating $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getCategory(): ?StreamCategory
    {
        return $this->category;
    }

    public function setCategory(?StreamCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAccessData(): ?StreamData
    {
        return $this->accessData;
    }

    public function setAccessData(?StreamData $accessData): self
    {
        $this->accessData = $accessData;

        return $this;
    }


}
