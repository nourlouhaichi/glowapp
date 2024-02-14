<?php

namespace App\Entity;

use App\Repository\ProgrammeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity(repositoryClass: ProgrammeRepository::class)]
class Programme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $categorie_pro = null;

    #[ORM\Column(length: 255)]
    private ?string $plan_pro = null;

    #[ORM\Column(length: 255)]
    private ?string $note_pro = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_pro = null;
    
    #[ORM\OneToMany(targetEntity: Objectif::class, mappedBy: 'programme')]
    private Collection $objectifs;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoriePro(): ?string
    {
        return $this->categorie_pro;
    }

    public function setCategoriePro(string $categorie_pro): static
    {
        $this->categorie_pro = $categorie_pro;

        return $this;
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
}
public function __toString()
    {
        
        return $this->categorie_pro;
    }
    
}
