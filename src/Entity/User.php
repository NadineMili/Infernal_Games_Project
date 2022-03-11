<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

use phpDocumentor\Reflection\DocBlock\Serializer;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    //private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Name is required")
     */
    private $name;
    /**
     * @var
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="lastName is required")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image= null ;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\OneToOne(targetEntity=Subscription::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $subscription;

    /**
     * @ORM\OneToMany(targetEntity=StreamComment::class, mappedBy="user")
     */
    private $streamComments;

    /**
     * @ORM\OneToMany(targetEntity=GameComment::class, mappedBy="user")
     */
    private $gameComment;


    /**
     * @ORM\OneToMany(targetEntity=Rating::class, mappedBy="user")
     */
    private $ratings;

    /**
     * @ORM\OneToMany(targetEntity=Commande::class, mappedBy="user")
     */
    private $commandes;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $activation_token;

    /**     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $reset_token;

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function __construct()
    {
        $this->streamComments = new ArrayCollection();
        $this->gameComment = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->commandes = new ArrayCollection();
    }

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile = null;
    public function serialize()
    {
        $this->image = base64_encode($this->image);
    }

    public function unserialize($serialized)
    {
        $this->image = base64_decode($this->image);

    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }
/*
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
*/
    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }


    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(?string $activation_token): self
    {
        $this->activation_token = $activation_token;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function setSubscription(?Subscription $subscription): self
    {
        // unset the owning side of the relation if necessary
        if ($subscription === null && $this->subscription !== null) {
            $this->subscription->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($subscription !== null && $subscription->getUser() !== $this) {
            $subscription->setUser($this);
        }

        $this->subscription = $subscription;

        return $this;
    }

    public function __toString(){
        //return $this->username;
        return $this->name;
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
            $streamComment->setUser($this);
        }

        return $this;
    }

    public function removeStreamComment(StreamComment $streamComment): self
    {
        if ($this->streamComments->removeElement($streamComment)) {
            // set the owning side to null (unless already changed)
            if ($streamComment->getUser() === $this) {
                $streamComment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GameComment>
     */
    public function getGameComment(): Collection
    {
        return $this->gameComment;
    }

    public function addGameComment(GameComment $gameComment): self
    {
        if (!$this->gameComment->contains($gameComment)) {
            $this->gameComment[] = $gameComment;
            $gameComment->setUser($this);
        }

        return $this;
    }

    public function removeGameComment(GameComment $gameComment): self
    {
        if ($this->gameComment->removeElement($gameComment)) {
            // set the owning side to null (unless already changed)
            if ($gameComment->getUser() === $this) {
                $gameComment->setUser(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setUser($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getUser() === $this) {
                $rating->setUser(null);
            }
        }

        return $this;
    }

}
