<?php

namespace App\Entity;

use App\Repository\StreamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=StreamRepository::class)
 */
class Stream
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("streams:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank(message="Title can't be blank!")
     * @Groups("streams:read")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Describe your stream!")
     * @Groups("streams:read")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=StreamRating::class, inversedBy="streams")
     * @Assert\NotBlank(message="Maturity rating is required!")
     * @Groups("streams:read")
     */
    private $rating;

    /**
     * @ORM\ManyToOne(targetEntity=StreamCategory::class, inversedBy="streams")
     * @Assert\NotBlank(message="What is your stream about?")
     * @Groups("streams:read")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=StreamData::class, inversedBy="streams")
     * @Groups("streams:read")
     */
    private $accessData;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("streams:read")
     */
    private $state;

    /**
     * @ORM\OneToMany(targetEntity=StreamComment::class, mappedBy="stream")
     */
    private $streamComments;

    public function __construct()
    {
        $this->streamComments = new ArrayCollection();
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

    public function getState(): ?bool
    {
        return $this->state;
    }

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection|StreamComment[]
     */
    public function getStreamComments(): Collection
    {
        return $this->streamComments;
    }

    public function addStreamComment(StreamComment $streamComment): self
    {
        if (!$this->streamComments->contains($streamComment)) {
            $this->streamComments[] = $streamComment;
            $streamComment->setStream($this);
        }

        return $this;
    }

    public function removeStreamComment(StreamComment $streamComment): self
    {
        if ($this->streamComments->removeElement($streamComment)) {
            // set the owning side to null (unless already changed)
            if ($streamComment->getStream() === $this) {
                $streamComment->setStream(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }

}
