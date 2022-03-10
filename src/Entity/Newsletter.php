<?php

namespace App\Entity;

use App\Repository\NewsletterRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=NewsletterRepository::class)
 */
class Newsletter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Can't leave title blank")
     * @Assert\Length(min="5",minMessage="Title too short. It should be at least 5")
     */
    private $titleF;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Can't leave content blank")
     * @Assert\Length(min="50",minMessage="Not enough content. It should be at least 50")
     */
    private $contentF;

    /**
     * @ORM\ManyToOne(targetEntity=Admin::class, inversedBy="newsletters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $sent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageF;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Can't leave title blank")
     * @Assert\Length(min="5",minMessage="Title too short. It should be at least 5")
     */
    private $titleS;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Can't leave content blank")
     * @Assert\Length(min="50",minMessage="Not enough content. It should be at least 50")

     */
    private $contentS;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageS;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Can't leave title blank")
     * @Assert\Length(min="5",minMessage="Title too short. It should be at least 5")
     */
    private $titleT;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Can't leave content blank")
     * @Assert\Length(min="50",minMessage="Not enough content. It should be at least 50")
     */
    private $contentT;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageT;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Can't leave title blank")
     * @Assert\Length(min="5",minMessage="Title too short. It should be at least 5")
     */
    private $titleIntro;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Can't leave content blank")
     * @Assert\Length(min="20",minMessage="Not enough content. It should be at least 20")
     */
    private $contentIntro;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleF(): ?string
    {
        return $this->titleF;
    }

    public function setTitleF(string $titleF): self
    {
        $this->titleF = $titleF;

        return $this;
    }

    public function getContentF(): ?string
    {
        return $this->contentF;
    }

    public function setContentF(string $contentF): self
    {
        $this->contentF = $contentF;

        return $this;
    }

    public function getAuthor(): ?Admin
    {
        return $this->author;
    }

    public function setAuthor(?Admin $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate($date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getSent(): ?bool
    {
        return $this->sent;
    }

    public function setSent(bool $sent): self
    {
        $this->sent = $sent;

        return $this;
    }

    public function getImageF(): ?string
    {
        return $this->imageF;
    }

    public function setImageF(string $imageF): self
    {
        $this->imageF = $imageF;

        return $this;
    }

    public function getTitleS(): ?string
    {
        return $this->titleS;
    }

    public function setTitleS(string $titleS): self
    {
        $this->titleS = $titleS;

        return $this;
    }

    public function getContentS(): ?string
    {
        return $this->contentS;
    }

    public function setContentS(string $contentS): self
    {
        $this->contentS = $contentS;

        return $this;
    }

    public function getImageS(): ?string
    {
        return $this->imageS;
    }

    public function setImageS(string $imageS): self
    {
        $this->imageS = $imageS;

        return $this;
    }

    public function getTitleT(): ?string
    {
        return $this->titleT;
    }

    public function setTitleT(string $titleT): self
    {
        $this->titleT = $titleT;

        return $this;
    }

    public function getContentT(): ?string
    {
        return $this->contentT;
    }

    public function setContentT(string $contentT): self
    {
        $this->contentT = $contentT;

        return $this;
    }

    public function getImageT(): ?string
    {
        return $this->imageT;
    }

    public function setImageT(string $imageT): self
    {
        $this->imageT = $imageT;

        return $this;
    }

    public function getTitleIntro(): ?string
    {
        return $this->titleIntro;
    }

    public function setTitleIntro(string $titleIntro): self
    {
        $this->titleIntro = $titleIntro;

        return $this;
    }

    public function getContentIntro(): ?string
    {
        return $this->contentIntro;
    }

    public function setContentIntro(string $contentIntro): self
    {
        $this->contentIntro = $contentIntro;

        return $this;
    }


}
