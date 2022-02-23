<?php

namespace App\Entity;

use App\Repository\StreamDataRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StreamDataRepository::class)
 */
class StreamData
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $streamer;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $streamKey;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Stream::class, mappedBy="accessData")
     */
    private $streams;

    public function __construct()
    {
        $this->streams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreamer(): ?User
    {
        return $this->streamer;
    }

    public function setStreamer(User $streamer): self
    {
        $this->streamer = $streamer;

        return $this;
    }

    public function getStreamKey(): ?string
    {
        return $this->streamKey;
    }

    public function setStreamKey(string $streamKey): self
    {
        $this->streamKey = $streamKey;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Stream[]
     */
    public function getStreams(): Collection
    {
        return $this->streams;
    }

    public function addStream(Stream $stream): self
    {
        if (!$this->streams->contains($stream)) {
            $this->streams[] = $stream;
            $stream->setAccessData($this);
        }

        return $this;
    }

    public function removeStream(Stream $stream): self
    {
        if ($this->streams->removeElement($stream)) {
            // set the owning side to null (unless already changed)
            if ($stream->getAccessData() === $this) {
                $stream->setAccessData(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->getStreamKey();
    }
}
