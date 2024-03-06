<?php

namespace App\Entity;

use App\Repository\ProgrammeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProgrammeRepository::class)]
class Programme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Plan is required.")]
    private ?string $plan_pro = null;

   
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Note is required.")]
    private ?string $note_pro = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(message: "Date is required.")]
    #[Assert\GreaterThanOrEqual(value: "today", message: "Date must be today or in the future.")]
    private ?\DateTimeInterface $date_pro = null;
    
    #[ORM\OneToMany(targetEntity: Objectif::class, mappedBy: 'programme' ,cascade: ['persist', 'remove'])]
    private Collection $objectifs;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "title is required.")]
    private ?string $titre_pro = null;

    #[ORM\ManyToOne(inversedBy: 'programs')]
    private ?Categorypro $categorypro = null;

    #[ORM\OneToMany(mappedBy: 'programe', targetEntity: Rating::class, cascade: ['remove'])]
    private Collection $ratings;

    #[ORM\OneToMany(mappedBy: 'idprog', targetEntity: Reservation::class, cascade: ['remove'])]
    private Collection $reservations;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Veuillez entrer la capacité !")]
    private ?int $placeDispo = null;

    #[ORM\Column]
    #[Assert\NotBlank( message:"Veuillez entrer le prix !")]
    private ?float $prix = null;

    #[ORM\OneToMany(mappedBy: 'progComment', targetEntity: CommentProg::class, cascade: ['remove'])]
    private Collection $comments;
   

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getPlanPro(): ?string
    {
        return $this->plan_pro;
    }

    public function setPlanPro(string $plan_pro): static
    {
        $this->plan_pro = $plan_pro;

        return $this;
    }

    public function getNotePro(): ?string
    {
        return $this->note_pro;
    }

    public function setNotePro(string $note_pro): static
    {
        $this->note_pro = $note_pro;

        return $this;
    }

    public function getDatePro(): ?\DateTimeInterface
    {
        return $this->date_pro;
    }

    public function setDatePro(\DateTimeInterface $date_pro): static
    {
        $this->date_pro = $date_pro;

        return $this;
    }
    public function __construct()
{
    $this->objectifs = new ArrayCollection();
    $this->ratings = new ArrayCollection();
    $this->reservations = new ArrayCollection();
    $this->comments = new ArrayCollection();
}
public function __toString()
    {
        
        return $this->categorie_pro;
    }

public function getImage(): ?string
{
    return $this->image;
}

public function setImage(?string $image): static
{
    $this->image = $image;

    return $this;
}

public function getTitrePro(): ?string
{
    return $this->titre_pro;
}

public function setTitrePro(string $titre_pro): static
{
    $this->titre_pro = $titre_pro;

    return $this;
}
    
public function getCategorypro(): ?Categorypro
{
    return $this->categorypro;
}

public function setCategorypro(?Categorypro $categorypro): static
{
    $this->categorypro = $categorypro;

    return $this;
}

/**
 * @return Collection<int, Rating>
 */
public function getRatings(): Collection
{
    return $this->ratings;
}

public function addRating(Rating $rating): static
{
    if (!$this->ratings->contains($rating)) {
        $this->ratings->add($rating);
        $rating->setPrograme($this);
    }

    return $this;
}

public function removeRating(Rating $rating): static
{
    if ($this->ratings->removeElement($rating)) {
        // set the owning side to null (unless already changed)
        if ($rating->getPrograme() === $this) {
            $rating->setPrograme(null);
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
        $reservation->setIdprog($this);
    }

    return $this;
}

public function removeReservation(Reservation $reservation): static
{
    if ($this->reservations->removeElement($reservation)) {
        // set the owning side to null (unless already changed)
        if ($reservation->getIdprog() === $this) {
            $reservation->setIdprog(null);
        }
    }

    return $this;
}


public function getPlaceDispo()
    {
        return $this->placeDispo;
    }

    /**
     * Set the value of placeDispo
     *
     * @return  self
     */ 
    public function setPlaceDispo($placeDispo)
    {
        $this->placeDispo = $placeDispo;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, CommentProg>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(CommentProg $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setProgComment($this);
        }

        return $this;
    }

    public function removeComment(CommentProg $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getProgComment() === $this) {
                $comment->setProgComment(null);
            }
        }

        return $this;
    }

    
}
