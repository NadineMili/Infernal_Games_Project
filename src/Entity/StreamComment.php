<?php

namespace App\Entity;

use App\Repository\StreamCommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=StreamCommentRepository::class)
 */
class StreamComment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("comments:read")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="streamComments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("comments:read")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Stream::class, inversedBy="streamComments")
     * @Groups("comments:read")
     */
    private $stream;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("comments:read")
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("comments:read")
     */
    private $timeStamp;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStream(): ?Stream
    {
        return $this->stream;
    }

    public function setStream(?Stream $stream): self
    {
        $this->stream = $stream;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getTimeStamp(): ?\DateTimeInterface
    {
        return $this->timeStamp;
    }

    public function setTimeStamp(\DateTimeInterface $timeStamp): self
    {
        $this->timeStamp = $timeStamp;

        return $this;
    }
}
