<?php

namespace App\Entity;

use App\Repository\GameCommentRepository;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text;


    public function getId(): ?int
    {
        return $this->id;
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
}
