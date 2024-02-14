<?php

namespace App\Entity;

use App\Repository\ObjectifRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ObjectifRepository::class)]
class Objectif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $cin = null;

    #[ORM\Column(length: 255)]
    private ?string $objectif_o = null;

    #[ORM\Column(length: 255)]
    private ?string $description_o = null;

    #[ORM\Column(length: 255)]
    private ?string $categorie_o = null;

    #[ORM\Column]
    private ?float $poid_o = null;

    #[ORM\Column]
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

    public function getIdo(): ?int
    {
        return $this->ido;
    }

    public function setIdo(int $ido): static
    {
        $this->ido = $ido;

        return $this;
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
