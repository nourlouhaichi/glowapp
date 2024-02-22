<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert; 

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[UniqueEntity(fields: ['cin'], message: 'There is already an account with this cin')]
#[UniqueEntity(fields: ['phone'], message: 'There is already an account with this phone number')]

class User implements UserInterface, PasswordAuthenticatedUserInterface
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
    




    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->roles = ['ROLE_USER'];
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
}
