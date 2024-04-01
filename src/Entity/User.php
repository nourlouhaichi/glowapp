<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert; 
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[UniqueEntity(fields: ['cin'], message: 'There is already an account with this cin')]
#[UniqueEntity(fields: ['phone'], message: 'There is already an account with this phone number')]

class User implements UserInterface, PasswordAuthenticatedUserInterface, TwoFactorInterface
{
    // #[ORM\Id]
    // #[ORM\GeneratedValue]
    // #[ORM\Column]
    // private ?int $id = null;

    #[ORM\Id]
    #[ORM\Column(length: 8)]
    #[Assert\NotBlank(message:"CIN is required")]
    #[Assert\Length(
        min: 8,
        max: 8,
        exactMessage: "CIN must contain exactly {{ limit }} digits"
    )]
    #[Assert\Regex(pattern: "/^\d+$/", message: "CIN must contain only digits")]
    private ?string $cin = null;


    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message:"Email is required")]
    #[Assert\Email(message:"Email format is invalid")]
    private ?string $email = null;


    #[ORM\Column]
    // #[Assert\NotBlank(message:"Role is required")]
    private array $roles = [];


    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    // #[Assert\NotBlank(message:"Password is required")]
    private ?string $password = null;


    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message:"Lastname is required")]
    #[Assert\Length(
        max: 30,
        maxMessage: "Lastname cannot be longer than {{ limit }} characters"
    )]
    #[Assert\Regex(
        pattern: "/^\D*$/",
        message: "Lastname cannot contain digits"
    )]
    private ?string $lastname = null;


    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message:"Firstname is required")]
    #[Assert\Length(
        max: 25,
        maxMessage: "Firstname cannot be longer than {{ limit }} characters"
    )]
    #[Assert\Regex(
        pattern: "/^\D*$/",
        message: "Firstname cannot contain digits"
    )]
    private ?string $firstname = null;


    #[ORM\Column(length: 25)]
    #[Assert\NotBlank(message:"Gender is required")]
    // #[Assert\Choice(choices: ['male', 'female'], message: "Gender should be one of: male or female")]
    private ?string $gender = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:"Date of birth is required")]
    #[Assert\LessThanOrEqual("today", message:"Date of birth cannot be in the future")]
    private ?\DateTimeInterface $datebirth = null;


    #[ORM\Column(length: 8)]
    #[Assert\NotBlank(message:"Phone number is required")]
    #[Assert\Length(
        min: 8,
        max: 8,
        exactMessage: "Phone number must be {{ limit }} digits"
    )]
    #[Assert\Regex(pattern: "/^\d+$/", message: "Phone number must contain only digits")]
    private ?string $phone = null;


    #[ORM\Column(type: 'datetime_immutable', options: ['default'=> 'CURRENT_TIMESTAMP'])] //saisir date creation automatique
    private ?\DateTimeImmutable $created_at = null;


    #[ORM\Column(type: 'boolean')]
    private ?bool $isBanned = false;
    

    #[ORM\Column(length: 255)]
    private ?string $profilePicture = null;

    
    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;


    #[ORM\Column(length: 10)]
    private ?string $authCode = null;

    #[ORM\OneToMany(mappedBy: 'userpub', targetEntity: Publication::class)]
    private Collection $publications;

    #[ORM\OneToMany(mappedBy: 'usercmn', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'usereve', targetEntity: Event::class)]
    private Collection $events;

    #[ORM\OneToMany(mappedBy: 'userres', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\OneToMany(mappedBy: 'userprod', targetEntity: Produit::class)]
    private Collection $produits;

    #[ORM\OneToMany(mappedBy: 'userprog', targetEntity: Programme::class)]
    private Collection $programs;

    #[ORM\OneToMany(mappedBy: 'userob', targetEntity: Objectif::class)]
    private Collection $objectifs;

    #[ORM\OneToMany(mappedBy: 'usercmnp', targetEntity: CommentProg::class)]
    private Collection $commentprogs;



    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();

        $this->roles = ['ROLE_USER'];
        
        if (!$this->profilePicture) {
            if ($this->gender == "female") {
                $this->setProfilePicture("Fprofile.png");
            } else {
                $this->setProfilePicture("Mprofile.png");
            }
        }
        $this->publications = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->produits = new ArrayCollection();
        $this->programs = new ArrayCollection();
        $this->objectifs = new ArrayCollection();
        $this->commentprogs = new ArrayCollection();
                            
    }




    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

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

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
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
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getDatebirth(): ?\DateTimeInterface
    {
        return $this->datebirth;
    }

    public function setDatebirth(\DateTimeInterface $datebirth): static
    {
        $this->datebirth = $datebirth;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getIsBanned(): ?bool
    {
        return $this->isBanned;
    }

    public function setIsBanned(bool $isBanned): static
    {
        $this->isBanned = $isBanned;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(string $profilePicture): static
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function isEmailAuthEnabled() : bool 
    {
        return true;
    }

    public function getEmailAuthRecipient() : string 
    {
        return $this->email;
    }

    public function getEmailAuthCode(): string 
    {
        if (null == $this->authCode) 
        {
            throw new \LogicException('The email authentication code was not set');
        }
        return $this->authCode;
    }

    public function setEmailAuthCode(string $authCode): void 
    {
        $this->authCode = $authCode;
    }

    /**
     * @return Collection<int, Publication>
     */
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    public function addPublication(Publication $publication): static
    {
        if (!$this->publications->contains($publication)) {
            $this->publications->add($publication);
            $publication->setUserpub($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): static
    {
        if ($this->publications->removeElement($publication)) {
            // set the owning side to null (unless already changed)
            if ($publication->getUserpub() === $this) {
                $publication->setUserpub(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setUsercmn($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUsercmn() === $this) {
                $comment->setUsercmn(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setUsereve($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getUsereve() === $this) {
                $event->setUsereve(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setUserres($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getUserres() === $this) {
                $reservation->setUserres(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setUserprod($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getUserprod() === $this) {
                $produit->setUserprod(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Programme>
     */
    public function getPrograms(): Collection
    {
        return $this->programs;
    }

    public function addProgram(Programme $program): static
    {
        if (!$this->programs->contains($program)) {
            $this->programs->add($program);
            $program->setUserprog($this);
        }

        return $this;
    }

    public function removeProgram(Programme $program): static
    {
        if ($this->programs->removeElement($program)) {
            // set the owning side to null (unless already changed)
            if ($program->getUserprog() === $this) {
                $program->setUserprog(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Objectif>
     */
    public function getObjectifs(): Collection
    {
        return $this->objectifs;
    }

    public function addObjectif(Objectif $objectif): static
    {
        if (!$this->objectifs->contains($objectif)) {
            $this->objectifs->add($objectif);
            $objectif->setUserob($this);
        }

        return $this;
    }

    public function removeObjectif(Objectif $objectif): static
    {
        if ($this->objectifs->removeElement($objectif)) {
            // set the owning side to null (unless already changed)
            if ($objectif->getUserob() === $this) {
                $objectif->setUserob(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommentProg>
     */
    public function getCommentprogs(): Collection
    {
        return $this->commentprogs;
    }

    public function addCommentprog(CommentProg $commentprog): static
    {
        if (!$this->commentprogs->contains($commentprog)) {
            $this->commentprogs->add($commentprog);
            $commentprog->setUsercmnp($this);
        }

        return $this;
    }

    public function removeCommentprog(CommentProg $commentprog): static
    {
        if ($this->commentprogs->removeElement($commentprog)) {
            // set the owning side to null (unless already changed)
            if ($commentprog->getUsercmnp() === $this) {
                $commentprog->setUsercmnp(null);
            }
        }

        return $this;
    }

}
