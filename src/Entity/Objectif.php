<?php

namespace App\Entity;

use App\Repository\ObjectifRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ObjectifRepository::class)]
class Objectif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotNull(message:"The CIN must not be null.")]
    #[Assert\Positive(message:"The CIN must be a positive number.")]
    private ?int $cin = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"The objective cannot be empty.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "The objective cannot exceed {{ limit }} characters."
    )]
    private ?string $objectif_o = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"The description cannot be empty.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "The description cannot exceed {{ limit }} characters."
    )]
    private ?string $description_o = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"The category cannot be empty.")]
    private ?string $categorie_o = null;

    #[ORM\Column]
    #[Assert\NotNull(message:"The weight must not be null.")]
    #[Assert\Positive(message:"he weight must be a positive number.")]
    private ?float $poid_o = null;

    #[ORM\Column]
    #[Assert\NotNull(message:"The height must not be null.")]
    #[Assert\Positive(message:"The height must be a positive number.")]
    private ?float $taille_o = null;

    
    #[ORM\ManyToOne(targetEntity: Programme::class, inversedBy: 'objectifs')]
    private ?Programme $programme;
    public function getProgramme(): ?Programme
    {
        return $this->programme;
    }
    public function setProgramme(?Programme $programme): self
    {
        $this->programme = $programme;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getObjectifO(): ?string
    {
        return $this->objectif_o;
    }

    public function setObjectifO(string $objectif_o): static
    {
        $this->objectif_o = $objectif_o;

        return $this;
    }

    public function getDescriptionO(): ?string
    {
        return $this->description_o;
    }

    public function setDescriptionO(string $description_o): static
    {
        $this->description_o = $description_o;

        return $this;
    }

    public function getCategorieO(): ?string
    {
        return $this->categorie_o;
    }

    public function setCategorieO(string $categorie_o): static
    {
        $this->categorie_o = $categorie_o;

        return $this;
    }

    public function getPoidO(): ?float
    {
        return $this->poid_o;
    }

    public function setPoidO(float $poid_o): static
    {
        $this->poid_o = $poid_o;

        return $this;
    }

    public function getTailleO(): ?float
    {
        return $this->taille_o;
    }

    public function setTailleO(float $taille_o): static
    {
        $this->taille_o = $taille_o;

        return $this;
    }

}
