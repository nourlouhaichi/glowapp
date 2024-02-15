<?php

namespace App\Entity;

use App\Repository\PublicationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PublicationRepository::class)]
class Publication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre_p = null;

    #[ORM\Column(length: 255)]
    private ?string $type_p = null;

    #[ORM\Column(length: 255)]
    private ?string $contenue_p = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datecr_p = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreP(): ?string
    {
        return $this->titre_p;
    }

    public function setTitreP(string $titre_p): static
    {
        $this->titre_p = $titre_p;

        return $this;
    }

    public function getTypeP(): ?string
    {
        return $this->type_p;
    }

    public function setTypeP(string $type_p): static
    {
        $this->type_p = $type_p;

        return $this;
    }

    public function getContenueP(): ?string
    {
        return $this->contenue_p;
    }

    public function setContenueP(string $contenue_p): static
    {
        $this->contenue_p = $contenue_p;

        return $this;
    }

    public function getDatecrP(): ?\DateTimeInterface
    {
        return $this->datecr_p;
    }

    public function setDatecrP(\DateTimeInterface $datecr_p): static
    {
        $this->datecr_p = $datecr_p;

        return $this;
    }
}
